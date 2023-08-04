## Exception

| type specifier | allowed value types |
|----------------|---------------------|
| emessage       | Exception           |
| ecode          | Exception           |
| efile          | Exception           |
| eline          | Exception           |
| etrace         | Exception           |

```php
$intlFormat = (new Budgegeria\IntlFormat\Factory())->createIntlFormat('en_US');

echo $intlFormat->format('Error happened: %emessage', new Exception('Error-Message'));
// "Error happened: Error-Message"
```

[<- back](index.md)
