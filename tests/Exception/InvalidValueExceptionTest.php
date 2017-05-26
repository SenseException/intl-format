<?php

namespace Budgegeria\IntlFormat\Tests\Exception;

use Budgegeria\IntlFormat\Exception\IntlFormatException;
use Budgegeria\IntlFormat\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class InvalidValueExceptionTest extends TestCase
{
    /**
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidValueException
     * @expectedExceptionMessage Invalid type "string" of value. Allowed types: "integer, double".
     * @expectedExceptionCode 10
     */
    public function testInvalidValueType()
    {
        throw InvalidValueException::invalidValueType('foo', ['integer', 'double']);
    }

    /**
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidValueException
     * @expectedExceptionMessage "foo" is not a valid locale.
     * @expectedExceptionCode 20
     */
    public function testInvalidLocale()
    {
        throw InvalidValueException::invalidLocale('foo');
    }

    public function testParentClass()
    {
        $this->assertInstanceOf(IntlFormatException::class, new InvalidValueException());
    }
}