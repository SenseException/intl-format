<?php

namespace Budgegeria\IntlFormat\Tests\Exception;

use Budgegeria\IntlFormat\Exception\IntlFormatException;
use Budgegeria\IntlFormat\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class InvalidValueExceptionTest extends TestCase
{
    public function testInvalidValueType()
    {
        self::expectException(InvalidValueException::class);
        self::expectExceptionCode(10);
        self::expectExceptionMessage('Invalid type "string" of value. Allowed types: "integer, double".');

        throw InvalidValueException::invalidValueType('foo', ['integer', 'double']);
    }

    public function testInvalidLocale()
    {
        self::expectException(InvalidValueException::class);
        self::expectExceptionCode(20);
        self::expectExceptionMessage('"foo" is not a valid locale.');

        throw InvalidValueException::invalidLocale('foo');
    }

    public function testParentClass()
    {
        self::assertInstanceOf(IntlFormatException::class, new InvalidValueException());
    }
}