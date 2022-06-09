## Basic Usage

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

| type specifier               | allowed value types                                |
|------------------------------|----------------------------------------------------|
| duration                     | integer                                            |
| date (alias for date_medium) | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_short                   | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_medium                  | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_long                    | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_full                    | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_weekday                 | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_weekday_abbr            | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_month_name              | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_month_name_abbr         | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_year                    | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_month                   | integer, DateTime, DateTimeImmutable, IntlCalendar |
| date_day                     | integer, DateTime, DateTimeImmutable, IntlCalendar |
| time (alias for time_medium) | integer, DateTime, DateTimeImmutable, IntlCalendar |
| time_medium                  | integer, DateTime, DateTimeImmutable, IntlCalendar |
| time_long                    | integer, DateTime, DateTimeImmutable, IntlCalendar |
| time_full                    | integer, DateTime, DateTimeImmutable, IntlCalendar |
| quarter                      | integer, DateTime, DateTimeImmutable, IntlCalendar |
| quarter_abbr                 | integer, DateTime, DateTimeImmutable, IntlCalendar |
| quarter_name                 | integer, DateTime, DateTimeImmutable, IntlCalendar |
| week_of_month                | integer, DateTime, DateTimeImmutable, IntlCalendar |
| week_of_year                 | integer, DateTime, DateTimeImmutable, IntlCalendar |

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
echo $intlFormat->format('%date_weekday', new DateTime());
// Dienstag
echo $intlFormat->format('%date_weekday_abbr', new DateTime());
// Di.
echo $intlFormat->format('%quarter', new DateTime());
// 1
echo $intlFormat->format('%quarter_abbr', new DateTime());
// Q1
echo $intlFormat->format('%quarter_name', new DateTime());
// 1. Quartal
echo $intlFormat->format('%week_of_month', new DateTime('2016-03-01'));
// 1
echo $intlFormat->format('%week_of_year', new DateTime('2016-03-01'));
// 9

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
| timezone_id      | DateTimeInterface, DateTimeZone, IntlTimeZone |
| timezone_name    | DateTimeInterface, DateTimeZone, IntlTimeZone |
| timezone_short   | DateTimeInterface, DateTimeZone, IntlTimeZone |

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

echo $intlFormat->format('%timezone_id', new DateTime());
// "US/Arizona"
echo $intlFormat->format('%timezone_name', new DateTime());
// "Mountain Standard Time"
echo $intlFormat->format('%timezone_short', new DateTime());
// "MST"
```

| Added in 2.1 |
|--------------|
| timezone_id, timezone_name and timezone_short are introduced in to replace the now removed "timeseries" type specifiers because of obvious unfit naming. Use the "timezone" type specifiers from that version on. |

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

### Symbols

| type specifier   | allowed value types                                                                     |
|------------------|-----------------------------------------------------------------------------------------|
| currency_symbol  | string (valid [ISO 4217](https://en.wikipedia.org/wiki/ISO_4217) code or empty string)  |

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('fr_FR');

// Get the currency symbol for the british pound sterling in french format
echo $intlFormat->format('Preferred currency: %currency_symbol', 'GBP');
// "Preferred currency: £GB"

$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_GB');

// Get the currency symbol for the given locale
echo $intlFormat->format('Preferred currency: %currency_symbol', '');
// "Preferred currency: £"
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
