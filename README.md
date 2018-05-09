# splas-runner

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Style CI][ico-styleci]][link-styleci]
[![Code Coverage][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A cross-platform binary to change the desktop background from [Unsplash]. (uses the [splas-php] composer 
package)

## Install

Via Composer

```bash
$ composer global require pxgamer/splas-runner
```

Via Phive

```bash
$ phive install pxgamer/splas-runner
```

## Usage

- Get an API key from [Unsplash]
- Either set the `UNSPLASH_API_KEY` environment variable, or provide a `--key {key}` option in the command
- Run the binary using `splasr [options]`

#### Options

Name | Description
---- | -----
--keep / -k | Specify whether to keep or remove images. Defaults to remove any images before downloading.
--key | Specify your API key. Defaults to use the environment variable.
--interval / -i | Specify an integer (minutes) when running in looped mode. By default will exit after the first run.

#### Supported operating systems

OS      | Supported?
------- | ----------
Windows | ✓
Mac OSX | ✗
Ubuntu  | ✓
Linux   | ✗

_Note: Running in an unsupported OS will result in an ErrorException._

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email owzie123@gmail.com instead of using the issue tracker.

## Credits

- [pxgamer][link-author]
- [Unsplash]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/pxgamer/splas-runner.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/pxgamer/splas-runner/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/76461590/shield
[ico-code-quality]: https://img.shields.io/codecov/c/github/pxgamer/splas-runner.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pxgamer/splas-runner.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/pxgamer/splas-runner
[link-travis]: https://travis-ci.org/pxgamer/splas-runner
[link-styleci]: https://styleci.io/repos/76461590
[link-code-quality]: https://codecov.io/gh/pxgamer/splas-runner
[link-downloads]: https://packagist.org/packages/pxgamer/splas-runner
[link-author]: https://github.com/pxgamer
[link-contributors]: ../../contributors

[unsplash]: https://unsplash.com
[splas-php]: https://github.com/pxgamer/splas-php
