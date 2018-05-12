<?php

namespace App\Utils;

class FilterUsageCalculator
{
    private $values = [];

    /**
     * @param bool $value
     *
     * @return FilterUsageCalculator
     */
    public function addValue(bool $value) : self
    {
        $this->values[] = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function calculate() : int
    {
        return round(count(array_filter($this->values)) / count($this->values) * 100);
    }

    /**
     * @return FilterUsageCalculator
     */
    public function reset() : self
    {
        $this->values = [];

        return $this;
    }
}
