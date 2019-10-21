# Upgrade to 2.0

## New date type specifiers

The type specifiers `date_weekday_abbr`, `quarter`, `quarter_abbr` and `quarter_name`
got added to `Budgegeria\IntlFormat\Formatter\MessageFormatter`

## [BC-break] Changes in IntlFormat constructor

First argument: Previously an array of instances implementing `FormatterInterface`,
it now can be everything `iterable`.

Second argument: An instance of `MessageParserInterface` has to be explicit
injected.

## Introduce interfaces for IntlFormat / IntlFormat class became final

IntlFormat is now being `final`. For testing or implementation purposes,
use the new interfaces `Budgegeria\IntlFormat\IntlFormatInterface` and
`Budgegeria\IntlFormat\FormatterStorageInterface`.

## Use return type IntlFormatInterface instead IntlFormat in Factory

The `Factory` uses IntlFormatInterface as return type now.

# Upgrade to 1.5

## Set minimum PHP requirement to 7.2

You need to update your PHP version.

# Upgrade to 1.4

## New Formatters added

New Formatter class added:

 * `Budgegeria\IntlFormat\Formatter\CurrencySymbolFormatter`

# Upgrade to 1.3

## [Security] Possible ReDoS vulnerability fixed

A possible ReDoS vulnerability in `SprintfParser` got fixed.

## [Bugfix] Argument swapping beyond 9 is now possible

In previous versions it wasn't possible to use argument swapping beyond `%9$arg`.
`%10$arg`, `%11$arg` and above are now supported.

## [BC-break] `TimeZoneFormatter` constants are set to private

The constants for the type specifier in `TimeZoneFormatter` aren't accessible
from the outside of the class.

## [BC-break] Add return types to methods

In case `IntlFormat` got extended by a custom class and `addFormatter()` got
overridden, the added void return type has to be added in the custom class too.

Named constructor in `MessageFormatter` got return types.

## Add support for DateTimeImmutable

Because of some incompatibilities to the Intl-extension, DateTimeImmutable wasn't
usable in the older versions of Intl-Format. Support was added for Intl-Format 1.3.

## Set minimum PHP requirement to 7.1.5

You need to update your PHP version.

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