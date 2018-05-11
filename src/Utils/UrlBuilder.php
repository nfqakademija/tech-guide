<?php

namespace App\Utils;

use App\Entity\Shop;

class UrlBuilder
{
    /**
     * @var string
     */
    private $url;
    private $firstParamAdded = false;

    private $filterValueSeparator;
    private $firstFilterValueSeparator;
    private $filterSeparator;
    private $firstFilterSeparator;

    /**
     * @param string $homepage
     *
     * @return UrlBuilder
     */
    public function addHomePage(string $homepage) : self
    {
        $this->url .= $homepage;

        return $this;
    }

    /**
     * @param string $prefix
     *
     * @return UrlBuilder
     */
    public function addPrefix(string $prefix) : self
    {
        if ($prefix[0] !== '/' && $this->url[\strlen($this->url) - 1] !== '/') {
            $this->url .= '/';
        }
        $this->url .= $prefix;

        return $this;
    }

    /**
     * @param string $filter
     * @param array  $values
     *
     * @return UrlBuilder
     */
    public function addFilter(?string $filter, array $values) : self
    {
        if ($filter === null || empty($values)) {
            return $this;
        }

        if(!$this->firstParamAdded) {
            $this->firstParamAdded = true;
            if ($this->url[\strlen($this->url) - 1] !== $this->firstFilterSeparator) {
                $this->url .= $this->firstFilterSeparator;
            }
        }

        if ($this->firstParamAdded &&
            $filter[0] !== '/' &&
            $this->url[\strlen($this->url) - 1] !== $this->filterSeparator &&
            $this->url[\strlen($this->url) - 1] !== $this->firstFilterSeparator
        ) {
            $this->url .= $this->filterSeparator;
        }

        $this->url .= $filter;
        if ($this->url[\strlen($this->url) - 1] !== $this->firstFilterValueSeparator) {
            $this->url .= $this->firstFilterValueSeparator;
        }

        $and = '';
        foreach ($values as $value) {
            $this->url .= $and . $value;
            $and = $this->filterValueSeparator;
        }

        return $this;
    }

    /**
     * @param array $filtersAndValues
     *
     * @return UrlBuilder
     */
    public function addFilterArray(array $filtersAndValues) : self
    {
        $endFilters = [];
        foreach ($filtersAndValues as $filterAndValue) {
            if($filterAndValue[0][0] !== '/') {
                $this->addFilter($filterAndValue[0], $filterAndValue[1]);
                continue;
            }
            $endFilters[] = $filterAndValue;
        }

        foreach ($endFilters as $filterAndValue) {
            $this->addFilter($filterAndValue[0], $filterAndValue[1]);
        }

        return $this;
    }

    /**
     * @param string $separator
     * @param string $firstSeparator
     *
     * @return UrlBuilder
     */
    public function addFilterValueSeparators(string $separator, string $firstSeparator) : self
    {
        $this->filterValueSeparator = $separator;
        $this->firstFilterValueSeparator = $firstSeparator ?? $separator;
        return $this;
    }

    /**
     * @param string $separator
     * @param string $firstSeparator
     *
     * @return UrlBuilder
     */
    public function addFilterSeparators(string $separator, string $firstSeparator) : self
    {
        $this->filterSeparator = $separator;
        $this->firstFilterSeparator = $firstSeparator ?? $separator;
        return $this;
    }

    /**
     * @return UrlBuilder
     */
    public function reset() : self
    {
        $this->url = '';
        $this->firstParamAdded = false;

        $this->filterValueSeparator = null;
        $this->firstFilterValueSeparator = null;
        $this->filterSeparator = null;
        $this->firstFilterSeparator = null;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return UrlBuilder
     */
    public function setUrl(string $url) : self
    {
        $this->url = $url;

        return $this;
    }
}
