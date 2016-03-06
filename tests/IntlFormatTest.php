<?php

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\IntlFormat;

class IntlFormatTest extends \PHPUnit_Framework_TestCase
{
    public function testFormatUnknown()
    {
        $message1 = 'Hello   %world';
        $message2 = '%';
        $message3 = 'count: %%d';

        $formatter = new IntlFormat([]);

        $this->assertSame($message1, $formatter->format($message1));
        $this->assertSame($message2, $formatter->format($message2));
        $this->assertSame($message3, $formatter->format($message3));
    }
}
