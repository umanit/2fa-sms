# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2025-03-24

- Initial release of the bundle 🎉.

### Added

- Add an overridable service for SMS message generation.

### Changed

- Allow `null` value on `TwoFactorSmsInterface::setSmsAuthCode`.

### Fixed

- Fix dynamic auth code texter service declaration.
- Add missing method in `AuthCodeTexterInterface`.

[Unreleased]: https://github.com/umanit/2fa-sms/compare/1.0.0...HEAD

[1.0.0]: https://github.com/umanit/2fa-sms/releases/tag/1.0.0
