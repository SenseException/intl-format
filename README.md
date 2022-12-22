# Intl-Format
A wrapper library for PHP to format and internationalize values in messages like sprintf

[![Latest Stable Version](https://poser.pugx.org/senseexception/intl-format/v/stable)](https://packagist.org/packages/senseexception/intl-format)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/senseexception/intl-format.svg)](https://packagist.org/packages/senseexception/intl-format)
[![Tests](https://github.com/SenseException/intl-format/workflows/Tests/badge.svg)](https://github.com/SenseException/intl-format/actions?query=workflow%3ATests)
[![Static Analysis](https://github.com/SenseException/intl-format/workflows/Static%20Analysis/badge.svg)](https://github.com/SenseException/intl-format/actions?query=workflow%3A%22Static+Analysis%22)
[![License](https://poser.pugx.org/senseexception/intl-format/license)](https://packagist.org/packages/senseexception/intl-format)

### Why using this library?

Internationalisation is a very important matter when a PHP project covers more than just one country. Every country has
its own format for numbers, date or time. The Intl component offers functionality to handle all the formats you need,
but not always in a simple way.

This library formats messages using the Intl component of PHP and offers a sprintf-like API.

Example:

```php
echo $intlFormat->format('Today\'s number is %number', 1000.3);
// echo "Today's number is 1'000,3" in case of locale de_CH
// echo "Today's number is 1,000.3" in case of locale en_US
```

It is also easy extensible with your own custom formats.

## Does it affect [GDPR](https://www.eugdpr.org/) somehow?

Intl-Format itself uses the given data (e.g. timezone, locale) only for formatting purposes with the help of the
PHP Intl extension.

## Installation

You can install this with [Composer](https://getcomposer.org/).

```
composer require senseexception/intl-format
```

## Documentation

Read more about this library in the [documentation](http://senseexception.github.io/intl-format).
