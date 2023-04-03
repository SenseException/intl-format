<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Budgegeria\IntlFormat\Formatter\ExceptionFormatter;
use Exception;
use PHPUnit\Framework\TestCase;
use Throwable;

class ExceptionFormatterTest extends TestCase
{
    private ExceptionFormatter $formatter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatter = new ExceptionFormatter();
    }

    /** @dataProvider provideTypeSpecifier */
    public function testHas(string $typeSpecifier): void
    {
        self::assertTrue($this->formatter->has($typeSpecifier));
    }

    public function testHasIsFalse(): void
    {
        self::assertFalse($this->formatter->has('foo'));
    }

    /** @dataProvider provideExceptions */
    public function testFormatValue(string $typeSpecifier, Throwable $value, mixed $expected): void
    {
        self::assertSame($expected, $this->formatter->formatValue($typeSpecifier, $value));
    }

    public function testFormatValueInvalidValue(): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessageMatches('/"' . Throwable::class . '"/');
        $this->expectExceptionCode(10);

        $this->formatter->formatValue('emessage', 1);
    }

    /** @return string[][] */
    public static function provideTypeSpecifier(): array
    {
        return [
            'emessage' => ['emessage'],
            'ecode' => ['ecode'],
            'efile' => ['efile'],
            'eline' => ['eline'],
            'etrace' => ['etrace'],
        ];
    }

    /** @return array<array{string, Throwable, mixed}> */
    public static function provideExceptions(): array
    {
        $e = new Exception('foo', 42);

        return [
            'emessage' => ['emessage', $e, $e->getMessage()],
            'ecode' => ['ecode', $e, (string) $e->getCode()],
            'efile' => ['efile', $e, $e->getFile()],
            'eline' => ['eline', $e, (string) $e->getLine()],
            'etrace' => ['etrace', $e, $e->getTraceAsString()],
        ];
    }
}
