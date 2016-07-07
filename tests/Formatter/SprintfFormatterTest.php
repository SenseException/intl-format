<?php

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Formatter\SprintfFormatter;

class SprintfFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $typeSpecifier
     * @dataProvider provideTypeSpecifier
     */
    public function testHas($typeSpecifier)
    {
        $formatter = new SprintfFormatter();

        $this->assertTrue($formatter->has($typeSpecifier));
    }

    public function testHasIsFalse()
    {
        $formatter = new SprintfFormatter();

        $this->assertFalse($formatter->has('int'));
    }

    /**
     * @return array
     */
    public function provideTypeSpecifier()
    {
        return [
            ['s'],
            ['-\'+-12.12f'],
        ];
    }
}
