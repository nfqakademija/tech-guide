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

    public function replaceWithFalse() : self
    {
        foreach ($this->values as $i => $iValue) {
            if ($this->values[$i]) {
                $this->values[$i] = false;
                break;
            }
        }

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

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     *
     * @return FilterUsageCalculator
     */
    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }
}
