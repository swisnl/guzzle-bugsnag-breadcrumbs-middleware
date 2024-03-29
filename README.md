# Guzzle middleware that logs Bugsnag breadcrumbs

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Buy us a tree][ico-treeware]][link-treeware]
[![Build Status][ico-github-actions]][link-github-actions]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![Made by SWIS][ico-swis]][link-swis]

This is a [middleware](https://docs.guzzlephp.org/en/stable/handlers-and-middleware.html#middleware) for Guzzle 7 that leaves [Bugsnag breadcrumbs](https://docs.bugsnag.com/platforms/php/other/#logging-breadcrumbs) for all requests.

:warning: Please make sure you don't log sensitive information to Bugsnag and properly configure this middleware to redact secrets. :warning:

## Install

Via Composer

``` bash
composer require swisnl/guzzle-bugsnag-breadcrumbs-middleware
```

## Usage

``` php
use Bugsnag\Client as Bugsnag;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\HandlerStack;
use Swis\Guzzle\Bugsnag\BreadcrumbMiddleware;

$bugsnag = Bugsnag::make();

$stack = HandlerStack::create();
$stack->push(new BreadcrumbMiddleware($bugsnag));
$client = new Guzzle(['handler' => $stack]);
```

Now when you send a request, a Bugsnag breadcrumb is logged with the following metadata:

* method
* uri
* status code
* response body (summary), in case of client or server exceptions (status code >= 400)
* duration

### Laravel

Using Laravel? Simply use the factory method to create the middleware from the Bugsnag facade: `BreadcrumbMiddleware::fromFacade()`.

## Config

You can configure the middleware using the constructor arguments:

### `$bugsnag`
Your (preconfigured) Bugsnag client.

### `$name`
The name of the breadcrumb.

### `$redactedStrings`
A list of secret strings, such as API keys, that should be filtered out of the metadata.

### `$truncateBodyAt`
The length of the response body summary, which is added to the breadcrumb in case of client or server exceptions. Use null to disable logging response/request body.

By default, it does not log the request body and only logs the response body in case of client or server exceptions (status code >= 400). If you'd like to change this behaviour, you can provide your own `GuzzleHttp\BodySummarizerInterface` implementation. You can use the default `GuzzleHttp\BodySummarizer` for example, to log all request and response bodies. Please be aware not to log sensitive information!

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email security@swis.nl instead of using the issue tracker.

## Credits

- [Jasper Zonneveld][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**][link-treeware] to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

## SWIS :heart: Open Source

[SWIS][link-swis] is a web agency from Leiden, the Netherlands. We love working with open source software. 

[ico-version]: https://img.shields.io/packagist/v/swisnl/guzzle-bugsnag-breadcrumbs-middleware.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-treeware]: https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen.svg?style=flat-square
[ico-github-actions]: https://img.shields.io/github/checks-status/swisnl/guzzle-bugsnag-breadcrumbs-middleware/master?label=tests&style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/swisnl/guzzle-bugsnag-breadcrumbs-middleware.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/swisnl/guzzle-bugsnag-breadcrumbs-middleware.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/swisnl/guzzle-bugsnag-breadcrumbs-middleware.svg?style=flat-square
[ico-swis]: https://img.shields.io/badge/%F0%9F%9A%80-made%20by%20SWIS-%230737A9.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/swisnl/guzzle-bugsnag-breadcrumbs-middleware
[link-github-actions]: https://github.com/swisnl/guzzle-bugsnag-breadcrumbs-middleware/actions/workflows/tests.yml
[link-scrutinizer]: https://scrutinizer-ci.com/g/swisnl/guzzle-bugsnag-breadcrumbs-middleware/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/swisnl/guzzle-bugsnag-breadcrumbs-middleware
[link-downloads]: https://packagist.org/packages/swisnl/guzzle-bugsnag-breadcrumbs-middleware
[link-treeware]: https://plant.treeware.earth/swisnl/guzzle-bugsnag-breadcrumbs-middleware
[link-author]: https://github.com/JaZo
[link-contributors]: ../../contributors
[link-swis]: https://www.swis.nl
