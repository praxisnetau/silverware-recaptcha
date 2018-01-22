# SilverWare Recaptcha Module

[![Latest Stable Version](https://poser.pugx.org/silverware/recaptcha/v/stable)](https://packagist.org/packages/silverware/recaptcha)
[![Latest Unstable Version](https://poser.pugx.org/silverware/recaptcha/v/unstable)](https://packagist.org/packages/silverware/recaptcha)
[![License](https://poser.pugx.org/silverware/recaptcha/license)](https://packagist.org/packages/silverware/recaptcha)

Provides a [Google Recaptcha][recaptcha] Spam Guard for use with [SilverStripe v4][silverstripe-framework] forms.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Issues](#issues)
- [Contribution](#contribution)
- [Attribution](#attribution)
- [Maintainers](#maintainers)
- [License](#license)

## Requirements

- [SilverStripe Framework v4][silverstripe-framework]
- [SilverWare Spam Guard][silverware-spam-guard]

## Installation

Installation is via [Composer][composer]:

```
$ composer require silverware/recaptcha
```

## Configuration

In order to use Recaptcha as the default spam guard for all forms, you will need to
set it as the `DefaultSpamGuard` in your YAML configuration:

```yml
SilverStripe\Core\Injector\Injector:
  DefaultSpamGuard:
    class: SilverWare\Recaptcha\Guards\RecaptchaGuard
```

Additionally, you will need to create public and private API keys via the [Google Recaptcha admin][recaptcha-admin].
Google refers to these as a "site key" and a "secret", respectively. Once you
have created your keys, add them to your YAML configuration:

```yml
SilverWare\Recaptcha\Fields\RecaptchaField:
  public_api_key: '<your-site-key>'
  private_api_key: '<your-secret>'
```

Each `RecaptchaField` has a config array which defines the data attributes for the
Recaptcha element. You can define the default config by adding the following to your
YAML configuration:

```yml
SilverWare\Recaptcha\Fields\RecaptchaField:
  default_config:
    theme: dark
    size: compact
```

This would configure each instance of Recaptcha to use the dark theme and compact size.

## Issues

Please use the [issue tracker][issues] for bug reports and feature requests.

## Contribution

Your contributions are gladly welcomed to help make this project better.
Please see [contributing](CONTRIBUTING.md) for more information.

## Attribution

- Makes use of [Guzzle][guzzle] by [Michael Dowling](https://github.com/mtdowling) and others.

## Maintainers

[![Colin Tucker](https://avatars3.githubusercontent.com/u/1853705?s=144)](https://github.com/colintucker) | [![Praxis Interactive](https://avatars2.githubusercontent.com/u/1782612?s=144)](https://www.praxis.net.au)
---|---
[Colin Tucker](https://github.com/colintucker) | [Praxis Interactive](https://www.praxis.net.au)

## License

[BSD-3-Clause](LICENSE.md) &copy; Praxis Interactive

[composer]: https://getcomposer.org
[recaptcha]: https://www.google.com/recaptcha
[recaptcha-admin]: https://www.google.com/recaptcha/admin
[silverstripe-framework]: https://github.com/silverstripe/silverstripe-framework
[silverware-spam-guard]: https://github.com/praxisnetau/silverware-spam-guard
[issues]: https://github.com/praxisnetau/silverware-recaptcha/issues
[guzzle]: https://github.com/guzzle/guzzle
