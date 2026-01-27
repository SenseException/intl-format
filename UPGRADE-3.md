# Upgrade to 3.3

## `Budgegeria\IntlFormat\Factory::createIntlFormat()` allows additional formatter instances

You can add your custom formatter during object creation with the factory

``` php
use Budgegeria\IntlFormat\Factory;

$intlFormatter = (new Factory())->createIntlFormat('en_US', [new MyCustomFormatter()]);
```

## Drop support of PHP 8.2

You need to update your PHP version.

# Upgrade to 3.2

## Drop support of PHP 8.0 and 8.1

You need to update your PHP version.

## Add `Budgegeria\IntlFormat\sprintf` function

An implementation of a sprintf function that uses intl-format was added. It
uses the default locale of the system.

# Upgrade to 3.1

## Add new type specifiers

`PrecisionNumberFormatter` supports new type specifiers

`*number_halfway_up`
`*number_halfway_down`
`*number_ceil`
`*number_floor`
`*number_halfeven`

``` php
intlFormat->format('Today\'s number is %02.2number_halfway_up', 1.225); // '1,23'
intlFormat->format('Today\'s number is %02.2number_halfway_down', 1.225); // '1,22'
intlFormat->format('Today\'s number is %02.2number_halfeven', 1.225); // '1,22'
intlFormat->format('Today\'s number is %02.2number_ceil', 1.221); // '1,23'
intlFormat->format('Today\'s number is %02.2number_floor', 1.229); // '1,22'
```

# Upgrade to 3.0

## Drop support of PHP 7

You need to update your PHP version.

## Remove deprecated type specifiers `timeseries_*`

`timeseries_*` type specifiers got removed. Use the `timezone_*` type specifiers.

## Change in interfaces

* Added `mixed` type in `\Budgegeria\IntlFormat\IntlFormatInterface::format()`
* Added `mixed` type in `\Budgegeria\IntlFormat\Formatter\FormatterInterface::formatValue()`
