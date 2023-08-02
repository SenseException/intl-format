## Symbols

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

[<- back](index.md)
