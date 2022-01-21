<?php

declare(strict_types=1);

namespace Swis\Guzzle\Bugsnag;

use Bugsnag\Breadcrumbs\Breadcrumb;
use Bugsnag\Client;
use GuzzleHttp\BodySummarizerInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class BreadcrumbMiddleware
{
    protected Client $bugsnag;

    protected string $name;

    protected array $redactedStrings;

    protected ?BodySummarizerInterface $bodySummarizer;

    /**
     * @param \Bugsnag\Client $bugsnag         your (preconfigured) Bugsnag client
     * @param string          $name            the name of the breadcrumb
     * @param array           $redactedStrings a list of secret strings, such as API keys, that should be filtered out of the metadata
     * @param int|null        $truncateBodyAt  the length of the response body summary, which is added to the breadcrumb in case of client or server exceptions
     */
    public function __construct(
        Client $bugsnag,
        string $name = 'HTTP request',
        array $redactedStrings = [],
        ?int $truncateBodyAt = 512
    ) {
        $this->bugsnag = $bugsnag;
        $this->name = $name;
        $this->redactedStrings = $redactedStrings;
        $this->bodySummarizer = $truncateBodyAt ? new BodySummarizer($truncateBodyAt) : null;
    }

    /**
     * @param \GuzzleHttp\BodySummarizerInterface|null $bodySummarizer
     *
     * @return $this
     */
    public function setBodySummarizer(?BodySummarizerInterface $bodySummarizer): self
    {
        $this->bodySummarizer = $bodySummarizer;

        return $this;
    }

    /**
     * @param callable $handler
     *
     * @return callable
     */
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            // Set starting time.
            $start = microtime(true);

            return $handler($request, $options)
                ->then(function (ResponseInterface $response) use ($start, $request) {
                    // After
                    $this->leaveBreadcrumb($start, $request, $response);

                    return $response;
                }, function (GuzzleException $exception) use ($start, $request) {
                    $response = $exception instanceof RequestException ? $exception->getResponse() : null;

                    $this->leaveBreadcrumb($start, $request, $response);

                    throw $exception;
                });
        };
    }

    protected function leaveBreadcrumb($start, RequestInterface $request, ResponseInterface $response = null): void
    {
        $this->bugsnag->leaveBreadcrumb(
            $this->name,
            Breadcrumb::PROCESS_TYPE,
            array_filter([
                'method' => $request->getMethod(),
                'uri' => $this->filter((string) $request->getUri()),
                'requestBody' => $this->filter($this->summarize($request)),
                'statusCode' => $response ? $response->getStatusCode() : null,
                'responseBody' => $response ? $this->filter($this->summarize($response)) : null,
                'time' => $this->formatDuration(microtime(true) - $start),
            ])
        );
    }

    protected function filter(?string $string): ?string
    {
        return $string ? str_replace($this->redactedStrings, '[FILTERED]', $string) : null;
    }

    protected function summarize(MessageInterface $request): ?string
    {
        return $this->bodySummarizer ? $this->bodySummarizer->summarize($request) : null;
    }

    /**
     * @param float $seconds
     *
     * @return string
     */
    protected function formatDuration(float $seconds): string
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000).'μs';
        }

        if ($seconds < 1) {
            return round($seconds * 1000, 2).'ms';
        }

        return round($seconds, 2).'s';
    }
}
