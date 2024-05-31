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

### sprintf function

A custom implementation of the function `sprintf` can be used to format values to php's default locale:

```php

// If en_US is the default locale of php

$date = new DateTime();

echo Budgegeria\IntlFormat\sprintf('Today is %date_short', $date);
// "Today is 3/1/16"
```

## Predefined formats

Here are some lists of predefined type specifiers and their formats, that are already usable in Intl-Format.

* [Numbers](numbers.md)
* [Date and Time](datetime.md)
* [Timezone](timezone.md)
* [Locale](locale.md)
* [Symbols](symbols.md)

## Additional formatters

These formatters aren't related to intl.

* [Exception](exception.md)
* [Sprintf](sprintf.md)

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
