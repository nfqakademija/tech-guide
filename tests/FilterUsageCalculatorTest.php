<?php

namespace App\Tests;

use App\Utils\FilterUsageCalculator;
use PHPUnit\Framework\TestCase;

class FilterUsageCalculatorTest extends TestCase
{
    public function testCalculate(): void
    {
        $calculator = new FilterUsageCalculator();
        for($i = 0; $i < 10; $i++) {
            $calculator->addValue(true);
        }

        $this->assertSame(100, $calculator->calculate());

        $calculator->replaceWithFalse();

        $this->assertSame(90, $calculator->calculate());

        $calculator->addValue(true);

        $this->assertSame(91, $calculator->calculate());
    }
}
