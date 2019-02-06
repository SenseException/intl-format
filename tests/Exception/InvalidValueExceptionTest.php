<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests\Exception;

use Budgegeria\IntlFormat\Exception\IntlFormatException;
use Budgegeria\IntlFormat\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class InvalidValueExceptionTest extends TestCase
{
    public function testInvalidValueType() : void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionCode(10);
        $this->expectExceptionMessage('Invalid type "string" of value. Allowed types: "integer, double".');

        throw InvalidValueException::invalidValueType('foo', ['integer', 'double']);
    }

    public function testInvalidLocale() : void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionCode(20);
        $this->expectExceptionMessage('"foo" is not a valid locale.');

        throw InvalidValueException::invalidLocale('foo');
    }

    public function testInvalidReturnType() : void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionCode(30);
        $this->expectExceptionMessage('Unexpected return type "integer"');

        throw InvalidValueException::invalidReturnType(1);
    }

    public function testParentClass() : void
    {
        self::assertInstanceOf(IntlFormatException::class, new InvalidValueException());
    }
}