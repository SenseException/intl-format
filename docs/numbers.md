## Numbers

| type specifier      | allowed value types                  |
|---------------------|--------------------------------------|
| number              | float, integer                       |
| number_halfway_down | float, integer                       |
| number_halfway_up   | float, integer                       |
| number_ceil         | float, integer                       |
| number_floor        | float, integer                       |
| integer             | integer                              |
| currency            | float, integer                       |
| percent             | float, integer                       |
| spellout            | float, integer                       |
| ordinal             | integer                              |

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

echo $intlFormat->format('%number', 1002.25);
// "1,002.25"
echo $intlFormat->format('%.4number', 1002.25);
// "1,002.2500"
echo $intlFormat->format('%02.2number_halfeven', 1.225);
// "1.22"
echo $intlFormat->format('%02.2number_halfway_down', 1.225);
// "1.22"
echo $intlFormat->format('%02.2number_halfway_up', 1.225);
// "1.23"
echo $intlFormat->format('%02.2number_ceil', 1.221);
// "1.23"
echo $intlFormat->format('%02.2number_floor', 1.229);
// "1.22"
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

[<- back](index.md)
