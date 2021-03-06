# BenatEspinaI18nRoutingBundle
> Bundle that provides some missing features to complement the [JMSI18nRoutingBundle][1]

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/76bfad5f-e980-4307-9830-5b900c33b45c/mini.png)](https://insight.sensiolabs.com/projects/76bfad5f-e980-4307-9830-5b900c33b45c)
[![Build Status](https://travis-ci.org/benatespina/I18nRoutingBundle.svg?branch=master)](https://travis-ci.org/benatespina/I18nRoutingBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/benatespina/I18nRoutingBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/benatespina/I18nRoutingBundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/benatespina/i18n-routing-bundle/downloads)](https://packagist.org/packages/benatespina/i18n-routing-bundle)
[![Latest Stable Version](https://poser.pugx.org/benatespina/i18n-routing-bundle/v/stable.svg)](https://packagist.org/packages/benatespina/i18n-routing-bundle)
[![Latest Unstable Version](https://poser.pugx.org/benatespina/i18n-routing-bundle/v/unstable.svg)](https://packagist.org/packages/benatespina/i18n-routing-bundle)

## Requirements
PHP >= 7.0</br>
Symfony >= 3.0

## Installation
The easiest way to install this bundle is using [Composer][2]
```bash
$ composer require benatespina/i18n-routing-bundle
```

## Documentation
All the documentation is stored in the `docs` folder.

[Show me the docs!](docs/index.md)

## Tests
This bundle is completely tested by **[PHPSpec][3], SpecBDD framework for PHP**.

Run the following command to launch tests:
```bash
$ vendor/bin/phpspec run -fpretty
```
Also, you can easily start a simple application to test the features that the bundle provides.
```bash
$ tests/Application/bin/console server:start --docroot=tests/Application/web
$ open http://127.0.0.1:8000
```

## Contributing
This bundle follows PHP coding standards, so pull requests need to execute the Fabien Potencier's [PHP-CS-Fixer][4].
Furthermore, if the PR creates some not-PHP file remember that you have to put the license header manually. In order
to simplify we provide a Composer script that wraps all the commands related with this process.
```bash
$ composer run-script cs
```

There is also a policy for contributing to this bundle. Pull requests must be explained step by step to make the
review process easy in order to accept and merge them. New methods or code improvements must come paired with
[PHPSpec][3] tests.

## Credits
This bundle is created by:
>
**@benatespina** - [benatespina@gmail.com](mailto:benatespina@gmail.com)

## Licensing Options
[![License](https://poser.pugx.org/benatespina/i18n-routing-bundle/license.svg)](https://github.com/benatespina/I18nRoutingBundle/blob/master/LICENSE)

[1]: http://jmsyst.com/bundles/JMSI18nRoutingBundle
[2]: http://getcomposer.org
[3]: http://www.phpspec.net/en/stable/
[4]: http://cs.sensiolabs.org/
