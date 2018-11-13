# Changelog

All notable changes to `splas-runner` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](https://keepachangelog.com) principles.

## [Unreleased]

## [v4.1.1] - 2018-07-05

### Changed
- Update to use automated Phar releases using Travis

## [v4.1.0] - 2018-07-05

### Added
- Add missing void return types

### Changed
- Update to use GuzzleHTTP for downloading images

## [v4.0.0] - 2018-05-09

### Changed
- Move the `splasr run` command to be the default (and only) command

## [v3.3.0] - 2018-02-08

### Added
- Add PHP 7.2 testing through Travis
- Update the dependency range for PHPUnit and PHP_CodeSniffer
- Add support for Symfony Console v4.*
- Change to use Box Phar version from Git tags

## [v3.2.0] - 2018-01-31

### Added
- Add Phive install instructions to the README

### Changed
- Change to use `webmozart/path-util` for cross-system home dir checking

### Removed
- Remove the `Environment` class

## [v3.1.0] - 2018-01-18

### Changed
- Change to use a `~/.splasr` directory to store background images
- Change the Windows method to use a registry edit ([#2](https://github.com/pxgamer/splas-runner/issues/2))

## [v3.0.3] - 2018-01-15

### Added
- Add Box support

## [v3.0.2] - 2017-12-06

### Fixed
- Correct legal name in license

## [v3.0.1] - 2017-11-20

### Fixed
- Correct the format of the license file

## [v3.0.0] - 2017-08-11

### Added
- Update to use `splas-php` ^4.0
- Update style of README
- Add additional missing project files
- Update the test suite

## [v2.0.7] - 2017-08-11

### Fixed
- Fix bug with switch of object to array

## [v2.0.6] - 2017-08-11

### Fixed
- Update to use `splas-php` v3.0.0

## [v2.0.5] - 2017-08-09

### Fixed
- Update package description

## [v2.0.4] - 2017-08-09

### Fixed
- Change Composer type to 'library' instead of a 'project'

## [v2.0.3] - 2017-08-09

### Added
- Add Ubuntu support
- Add OS support table to README

## [v2.0.2] - 2017-08-09

### Added
- Fix typo in command help

## [v2.0.1] - 2017-08-09

### Fixed
- Update version number in CLI

## [v2.0.1] - 2017-08-09

### Added
- Add badges

## [v2.0.0] - 2017-08-09

### Added
- Add Travis CI testing
- Add PHPUnit test suite

### Changed
- Change to be a binary package (`splasr`)

## v1.0.0 - 2017-03-07

### Added
- Initial release of Composer package

[Unreleased]: https://github.com/pxgamer/splas-runner/compare/master...develop
[v4.1.1]: https://github.com/pxgamer/splas-runner/compare/v4.1.0...v4.1.1
[v4.1.0]: https://github.com/pxgamer/splas-runner/compare/v4.0.0...v4.1.0
[v4.0.0]: https://github.com/pxgamer/splas-runner/compare/v3.3.0...v4.0.0
[v3.3.0]: https://github.com/pxgamer/splas-runner/compare/v3.2.0...v3.3.0
[v3.2.0]: https://github.com/pxgamer/splas-runner/compare/v3.1.0...v3.2.0
[v3.1.0]: https://github.com/pxgamer/splas-runner/compare/v3.0.3...v3.1.0
[v3.0.3]: https://github.com/pxgamer/splas-runner/compare/v3.0.2...v3.0.3
[v3.0.2]: https://github.com/pxgamer/splas-runner/compare/v3.0.1...v3.0.2
[v3.0.1]: https://github.com/pxgamer/splas-runner/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/pxgamer/splas-runner/compare/v2.0.7...v3.0.0
[v2.0.7]: https://github.com/pxgamer/splas-runner/compare/v2.0.6...v2.0.7
[v2.0.6]: https://github.com/pxgamer/splas-runner/compare/v2.0.5...v2.0.6
[v2.0.5]: https://github.com/pxgamer/splas-runner/compare/v2.0.4...v2.0.5
[v2.0.4]: https://github.com/pxgamer/splas-runner/compare/v2.0.3...v2.0.4
[v2.0.3]: https://github.com/pxgamer/splas-runner/compare/v2.0.2...v2.0.3
[v2.0.2]: https://github.com/pxgamer/splas-runner/compare/v2.0.1...v2.0.2
[v2.0.1]: https://github.com/pxgamer/splas-runner/compare/v2.0.0...v2.0.1
[v2.0.0]: https://github.com/pxgamer/splas-runner/compare/v1.0.0...v2.0.0
