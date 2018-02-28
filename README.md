# Intl-Format
A wrapper library for PHP to format and internationalize values in messages like sprintf

[![Build Status](https://travis-ci.org/SenseException/intl-format.svg?branch=master)](https://travis-ci.org/SenseException/intl-format)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SenseException/intl-format/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SenseException/intl-format/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/SenseException/intl-format/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/SenseException/intl-format/?branch=master)

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

## Usage

The common way to use Intl-Format is the Factory with already prepared formats for numbers, date, time and timezone values.
There are some predefined type specifier that can be used with a prefix percent symbol, just like in sprintf.

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

$date = new DateTime();

echo $intlFormat->format('Today is %date_short', $date);
// "Today is 3/1/16"
```

Like sprintf, you can have multiple values been formatted in your message:

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

$date = new DateTime();
$number = 1002.25;

echo $intlFormat->format('At %time_short the value was %number', $date, $number);
// "At 5:30 AM the value was 1,002.25"
```

Intl-Format also supports argument swapping:

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

$date = new DateTime();
$number = 1002.25;

echo $intlFormat->format('At %2$time_short the value was %1$number', $number, $date);
// "At 5:30 AM the value was 1,002.25"
```

In case something's not right with the value for the given type specifier, a
`Budgegeria\IntlFormat\Exception\InvalidValueException` is thrown. In case your type specifier isn't well formed to
the given values, a `Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException` is thown. Be sure to catch those
Exceptions.

You can catch every Exception of Intl-Format by using `Budgegeria\IntlFormat\Exception\IntlFormatException`.

## Predefined formats

Here are some lists of predefined type specifiers and their formats, that are already usable in Intl-Format.

### Numbers

| type specifier   | allowed value types                  |
|------------------|--------------------------------------|
| number           | float, integer                       |
| integer          | integer                              |
| currency         | float, integer                       |
| percent          | float, integer                       |
| spellout         | float, integer                       |
| ordinal          | integer                              |

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

echo $intlFormat->format('%number', 1002.25);
// "1,002.25"
echo $intlFormat->format('%.4number', 1002.25);
// "1,002.2500"
echo $intlFormat->format('%integer', 1002.25);
// "1,002"
echo $intlFormat->format('%percent', 1);
// "100%"
echo $intlFormat->format('%spellout', 1);
// "one"
echo $intlFormat->format('%ordinal', 1);
// "1st"
echo $intlFormat->format('%currency', 1.1);
// "$1.10"
```

The type specifier `number` also allows fraction digits similar to sprintf by adding a dot with an amount of digits.

### Date and time

| type specifier               | allowed value types                  |
|------------------------------|--------------------------------------|
| duration                     | integer                              |
| date (alias for date_medium) | integer, DateTime, IntlCalendar      |
| date_short                   | integer, DateTime, IntlCalendar      |
| date_medium                  | integer, DateTime, IntlCalendar      |
| date_long                    | integer, DateTime, IntlCalendar      |
| date_full                    | integer, DateTime, IntlCalendar      |
| date_weekday                 | integer, DateTime, IntlCalendar      |
| date_month_name              | integer, DateTime, IntlCalendar      |
| date_year                    | integer, DateTime, IntlCalendar      |
| date_month                   | integer, DateTime, IntlCalendar      |
| date_day                     | integer, DateTime, IntlCalendar      |
| time (alias for time_medium) | integer, DateTime, IntlCalendar      |
| time_medium                  | integer, DateTime, IntlCalendar      |
| time_long                    | integer, DateTime, IntlCalendar      |
| time_full                    | integer, DateTime, IntlCalendar      |

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

echo $intlFormat->format('%duration', 61);
// 1:01

$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('de_DE');

echo $intlFormat->format('%date_short', new DateTime());
// 01.03.16
echo $intlFormat->format('%date_medium', new DateTime());
// 01.03.2016
echo $intlFormat->format('%date_long', new DateTime());
// 1. März 2016
echo $intlFormat->format('%date_full', new DateTime());
// Dienstag, 1. März 2016

echo $intlFormat->format('%time_short', new DateTime());
// 01:20
echo $intlFormat->format('%time_medium', new DateTime());
// 01:20:50
echo $intlFormat->format('%time_long', new DateTime());
// 01:20:50 GMT
```

### Timezone

In some countries / locales there are time changes to daylight saving time (summer) and standard time (winter).
If an instance of DateTimeZone or IntlTimeZone is given, the formatter assumes the timezone is of the current server time.

| type specifier   | allowed value types                           |
|------------------|-----------------------------------------------|
| timeseries_id    | DateTimeInterface, DateTimeZone, IntlTimeZone |
| timeseries_name  | DateTimeInterface, DateTimeZone, IntlTimeZone |
| timeseries_short | DateTimeInterface, DateTimeZone, IntlTimeZone |

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

echo $intlFormat->format('%timeseries_id', new DateTime());
// "US/Arizona"
echo $intlFormat->format('%timeseries_name', new DateTime());
// "Mountain Standard Time"
echo $intlFormat->format('%timeseries_short', new DateTime());
// "MST"
```

### Locale

| type specifier   | allowed value types                           |
|------------------|-----------------------------------------------|
| region           | string (valid locale)                         |
| language         | string (valid locale)                         |

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

echo $intlFormat->format('They lived in %region', 'de_DE');
// "They lived in Germany"
echo $intlFormat->format('Do you speak %language?', 'de_DE');
// "Do you speak German?"
```

## Additional formatters

These formatters are optional and can be loaded after IntlFormat was created.

| formatter          | description                                            |
|--------------------|--------------------------------------------------------|
| SprintfFormatter   | Provides the known sprintf type specifier              |
| ExceptionFormatter | Allows to format messages, code and more of Throwables |

## Create your custom formatter

You can extend this library with your own type specifiers and formats by using the Formatter interface
`Budgegeria\IntlFormat\Formatter\FormatterInterface`.

```php
use Budgegeria\IntlFormat\Formatter\FormatterInterface;

class MyFormatter implements FormatterInterface
{
    public function has(string $typeSpecifier) : bool
    {
        return 'my_type_specifier' === $typeSpecifier;
    }
    
    public function formatValue(string $typeSpecifier, $value) : string
    {
        return $value . 'Bar';
    }
}

$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');
$intlFormat->addFormatter(new MyFormatter());

echo $intlFormat->format('My value is %my_type_specifier', 'foo');
// "My value is fooBar"
```

The method `FormatterInterface::has()` checks if the given type specifier is part of your formatter. It is used internally
in the class `Budgegeria\IntlFormat\IntlFormat`. If the type specifier is part of the formatter, the value will be
formatted with `FormatterInterface::formatValue()`. Be sure to check if the given value is a valid one for your formatter
or throw a `Budgegeria\IntlFormat\Exception\InvalidValueException`.

### Overwriting

You can override a type specifier by adding a formatter containing an already existing type specifier to the IntlFormat
instance. It doesn't override a whole Formatter instance, only individual type specifier.
