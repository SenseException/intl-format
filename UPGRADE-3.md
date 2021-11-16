# Upgrade to 3.0

## Drop support of PHP 7

You need to update your PHP version.

## Remove deprecated type specifiers `timeseries_*`

`timeseries_*` type specifiers got removed. Use the `timezone_*` type specifiers.

## Change in interfaces

* Added `mixed` type in `\Budgegeria\IntlFormat\IntlFormatInterface::format()`
* Added `mixed` type in `\Budgegeria\IntlFormat\Formatter\FormatterInterface::formatValue()`
