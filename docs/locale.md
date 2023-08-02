## Locale

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

[<- back](index.md)
