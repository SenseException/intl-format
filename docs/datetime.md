## Date and Time

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

[<- back](index.md)
