# Upgrade to 3.0

## Drop support of PHP 7

You need to update your PHP version.

## Remove deprecated type specifiers `timeseries_*`

`timeseries_*` type specifiers got removed. Use the `timezone_*` type specifiers.

## Change in interfaces

* Added `mixed` type in `\Budgegeria\IntlFormat\IntlFormatInterface::format()`
* Added `mixed` type in `\Budgegeria\IntlFormat\Formatter\FormatterInterface::formatValue()`

# Upgrade to 3.1

## Add halfway up rounding to `PrecisionNumberFormatter`

`PrecisionNumberFormatter` supports a new `*number_halfway_up` type specifier

``` php
intlFormat->format('Today\'s number is %02.2number_halfway_up', 1.225); // '1,23'
```
