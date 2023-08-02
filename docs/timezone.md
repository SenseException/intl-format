## Timezone

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

[<- back](index.md)
