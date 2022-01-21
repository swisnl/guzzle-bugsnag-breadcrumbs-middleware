<?php

declare(strict_types=1);

namespace Swis\Guzzle\Bugsnag\Tests;

use Bugsnag\Breadcrumbs\Breadcrumb;
use Bugsnag\Client;
use GuzzleHttp\BodySummarizerInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Swis\Guzzle\Bugsnag\BreadcrumbMiddleware;

class BreadcrumbMiddlewareTest extends TestCase
{
    public function testItLeavesABreadcrumbForSuccessfulRequests(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb');
        $middleware = new BreadcrumbMiddleware($bugsnag);

        // act
        $value = $middleware(fn () => $promise)($request, [])->wait();

        // assert
        $this->assertSame($response, $value);
    }

    public function testItLeavesABreadcrumbForUnsuccessfulRequests(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $exception = $this->createMock(GuzzleException::class);
        $promise = new Promise();
        $promise->reject($exception);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb');
        $middleware = new BreadcrumbMiddleware($bugsnag);

        // assert
        $this->expectExceptionObject($exception);

        // act
        $middleware(fn () => $promise)($request, [])->wait();
    }

    public function testItSetsTheCorrectName(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb')
            ->with(
                $this->equalTo('foo-bar')
            );
        $middleware = new BreadcrumbMiddleware($bugsnag, 'foo-bar');

        // act
        $middleware(fn () => $promise)($request, [])->wait();

        // assert
        // see expectations
    }

    public function testItSetsTheCorrectType(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb')
            ->with(
                $this->anything(),
                $this->equalTo(Breadcrumb::PROCESS_TYPE)
            );
        $middleware = new BreadcrumbMiddleware($bugsnag);

        // act
        $middleware(fn () => $promise)($request, [])->wait();

        // assert
        // see expectations
    }

    public function testItSetsTheMethod(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $request->method('getMethod')
            ->willReturn('GET');
        $response = $this->createMock(ResponseInterface::class);
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback(fn (array $metadata) => $metadata['method'] === 'GET')
            );
        $middleware = new BreadcrumbMiddleware($bugsnag);

        // act
        $middleware(fn () => $promise)($request, [])->wait();

        // assert
        // see expectations
    }

    public function testItSetsTheUri(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $request->method('getUri')
            ->willReturn('https://example.com');
        $response = $this->createMock(ResponseInterface::class);
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback(fn (array $metadata) => $metadata['uri'] === 'https://example.com')
            );
        $middleware = new BreadcrumbMiddleware($bugsnag);

        // act
        $middleware(fn () => $promise)($request, [])->wait();

        // assert
        // see expectations
    }

    public function testItSetsTheRequestBody(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $request->method('getBody')
            ->willReturn(Utils::streamFor('foo-bar'));
        $response = $this->createMock(ResponseInterface::class);
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback(fn (array $metadata) => $metadata['requestBody'] === 'foo-bar')
            );
        $bodySummarizer = $this->createMock(BodySummarizerInterface::class);
        $bodySummarizer->method('summarize')
            ->willReturn('foo-bar');
        $middleware = new BreadcrumbMiddleware($bugsnag);
        $middleware->setBodySummarizer($bodySummarizer);

        // act
        $middleware(fn () => $promise)($request, [])->wait();

        // assert
        // see expectations
    }

    public function testItSetsTheStatusCode(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn(200);
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback(fn (array $metadata) => $metadata['statusCode'] === 200)
            );
        $middleware = new BreadcrumbMiddleware($bugsnag);

        // act
        $middleware(fn () => $promise)($request, [])->wait();

        // assert
        // see expectations
    }

    public function testItSetsTheResponseBody(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn(Utils::streamFor('foo-bar'));
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback(fn (array $metadata) => $metadata['responseBody'] === 'foo-bar')
            );
        $bodySummarizer = $this->createMock(BodySummarizerInterface::class);
        $bodySummarizer->method('summarize')
            ->willReturn('foo-bar');
        $middleware = new BreadcrumbMiddleware($bugsnag);
        $middleware->setBodySummarizer($bodySummarizer);

        // act
        $middleware(fn () => $promise)($request, [])->wait();

        // assert
        // see expectations
    }

    public function testItSetsTheTime(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback(fn (array $metadata) => is_string($metadata['time']))
            );
        $middleware = new BreadcrumbMiddleware($bugsnag);

        // act
        $middleware(fn () => $promise)($request, [])->wait();

        // assert
        // see expectations
    }

    public function testItFiltersRedactedStrings(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $request->method('getUri')
            ->willReturn('https://example.com?auth=api-key');
        $request->method('getBody')
            ->willReturn(Utils::streamFor('foo-bar secret'));
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn(Utils::streamFor('foo-bar secret'));
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback(function (array $metadata) {
                    return $metadata['uri'] === 'https://example.com?auth=[FILTERED]'
                        && $metadata['requestBody'] === 'foo-bar [FILTERED]'
                        && $metadata['responseBody'] === 'foo-bar [FILTERED]';
                })
            );
        $bodySummarizer = $this->createMock(BodySummarizerInterface::class);
        $bodySummarizer->method('summarize')
            ->willReturn('foo-bar secret');
        $middleware = new BreadcrumbMiddleware($bugsnag, '', ['secret', 'api-key']);
        $middleware->setBodySummarizer($bodySummarizer);

        // act
        $middleware(fn () => $promise)($request, [])->wait();

        // assert
        // see expectations
    }

    public function testBodySummarizerCanBeDisabled(): void
    {
        // arrange
        $request = $this->createMock(RequestInterface::class);
        $request->method('getBody')
            ->willReturn(Utils::streamFor('foo-bar'));
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn(Utils::streamFor('foo-bar'));
        $response->method('getStatusCode')
            ->willReturn(500);
        $promise = new Promise();
        $promise->resolve($response);

        $bugsnag = $this->createMock(Client::class);
        $bugsnag->expects($this->once())
            ->method('leaveBreadcrumb')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback(fn (array $metadata) => !isset($metadata['requestBody'], $metadata['responseBody']))
            );
        $middleware = new BreadcrumbMiddleware($bugsnag, '', [], null);

        // act
        $middleware(fn () => $promise)($request, [])->wait();

        // assert
        // see expectations
    }
}
