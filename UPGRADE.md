# Upgrade to 1.2

## Drop support for HHVM

The support if HHVM was dropped in this version.

## Introduced exception code number

Exceptions of same type have now exception code to differ between use cases.

## Add support of fraction digits in "number" type specifier

The "number" type specifier now supports fraction digits with a sprintf-like
syntax. This was implemented in class

 * `Budgegeria\IntlFormat\Formatter\PrecisionNumberFormatter`

# Upgrade to 1.1

## New Formatters added

New Formatter classes added:

 * `Budgegeria\IntlFormat\Formatter\SprintfFormatter`
 * `Budgegeria\IntlFormat\Formatter\ExceptionFormatter`

which are not part of the default Factory method