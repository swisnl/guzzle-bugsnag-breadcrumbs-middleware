# Guzzle middleware that logs Bugsnag breadcrumbs

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Buy us a tree][ico-treeware]][link-treeware]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![Made by SWIS][ico-swis]][link-swis]

This is a [middleware](https://docs.guzzlephp.org/en/stable/handlers-and-middleware.html#middleware) for Guzzle 7 that leaves [Bugsnag breadcrumbs](https://docs.bugsnag.com/platforms/php/other/#logging-breadcrumbs) for all requests.

## Install

Via Composer

``` bash
$ composer require swisnl/guzzle-bugsnag-breadcrumbs-middleware
```

## Usage

``` php
$skeleton = new Swis\Guzzle\Bugsnag();
echo $skeleton->echoPhrase('Hello, League!');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
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
[ico-travis]: https://img.shields.io/travis/swisnl/guzzle-bugsnag-breadcrumbs-middleware/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/swisnl/guzzle-bugsnag-breadcrumbs-middleware.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/swisnl/guzzle-bugsnag-breadcrumbs-middleware.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/swisnl/guzzle-bugsnag-breadcrumbs-middleware.svg?style=flat-square
[ico-swis]: https://img.shields.io/badge/%F0%9F%9A%80-made%20by%20SWIS-%230737A9.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/swisnl/guzzle-bugsnag-breadcrumbs-middleware
[link-travis]: https://travis-ci.org/swisnl/guzzle-bugsnag-breadcrumbs-middleware
[link-scrutinizer]: https://scrutinizer-ci.com/g/swisnl/guzzle-bugsnag-breadcrumbs-middleware/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/swisnl/guzzle-bugsnag-breadcrumbs-middleware
[link-downloads]: https://packagist.org/packages/swisnl/guzzle-bugsnag-breadcrumbs-middleware
[link-treeware]: https://plant.treeware.earth/swisnl/guzzle-bugsnag-breadcrumbs-middleware
[link-author]: https://github.com/swisnl
[link-contributors]: ../../contributors
[link-swis]: https://www.swis.nl
