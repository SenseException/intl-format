## Sprintf

This introduces the [sprintf type specifiers](https://www.php.net/manual/en/function.sprintf.php) to IntlFormat,
but doesn't format values with the intl extension.

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

echo $intlFormat->format('Hello %s', 'world');
// "Hello world"
```

[<- back](index.md)
