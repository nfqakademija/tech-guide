<?php

namespace App\Utils;

class UrlBuilder
{
    private $url;

    private $firstParamAdded = false;
    private $repeatingFilter = false;

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

        if (!$this->firstParamAdded) {
            $this->firstParamAdded = true;
            if ($this->url[\strlen($this->url) - 1] !== $this->firstFilterSeparator) {
                $this->url .= $this->firstFilterSeparator;
            }
        }

        if ($this->firstParamAdded &&
            $filter[0] !== $this->firstFilterSeparator &&
            $this->url[\strlen($this->url) - 1] !== $this->filterSeparator &&
            $this->url[\strlen($this->url) - 1] !== $this->firstFilterSeparator
        ) {
            $this->url .= $this->filterSeparator;
        }

        if (!$this->repeatingFilter) {
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

        foreach ($values as $value) {
            $this->url .=
                $filter .
                $this->firstFilterValueSeparator .
                $value .
                $this->filterSeparator;
        }

        $this->url = substr($this->url, 0, -1);

        return $this;
    }

    /**
     * @param string $filter
     *
     * @return UrlBuilder
     */
    public function removeFilter(string $filter) : bool
    {
        if ($this->repeatingFilter) {
            preg_match_all(
                '#' . preg_quote($filter, '#') .
                    $this->firstFilterValueSeparator . '(\w+)(?:' . $this->filterSeparator . '|$)#is',
                $this->url,
                $matches
            );

            $isReplaced = false;
            foreach ($matches[0] as $match) {
                $isReplaced = true;
                $this->url = str_replace($match, '', $this->url);
            }

            return $isReplaced;
        }


        preg_match(
            '#' . $filter . $this->firstFilterValueSeparator . '(.*)(?:' . $this->filterSeparator . '|$)#is',
            $this->url,
            $match
        );

        $isReplaced = false;
        if (isset($match[0])) {
            $isReplaced = true;
            $replaceValue = explode($this->filterSeparator, $match[0])[0];
            if (strpos($this->url, $replaceValue . $this->filterSeparator)) {
                $replaceValue .= $this->filterSeparator;
            }
            $this->url = str_replace($replaceValue, '', $this->url);
        }
        return $isReplaced;
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
            if ($filterAndValue[0][0] !== '/') {
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
        $this->repeatingFilter = false;

        $this->filterValueSeparator = null;
        $this->firstFilterValueSeparator = null;
        $this->filterSeparator = null;
        $this->firstFilterSeparator = null;

        return $this;
    }

    /**
     * @param int $page
     *
     * @return string
     */
    public function getPage(int $page) : string
    {
        if ($this->firstFilterSeparator === '/') {
            return $this->url . '/' . $page;
        }

        return $this->url . $this->filterSeparator . 'p' . $this->firstFilterValueSeparator . $page;
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

    /**
     * @return bool
     */
    public function isRepeatingFilter(): bool
    {
        return $this->repeatingFilter;
    }

    /**
     * @param bool $repeatingFilter
     *
     * @return UrlBuilder
     */
    public function setRepeatingFilter(bool $repeatingFilter): self
    {
        $this->repeatingFilter = $repeatingFilter;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFirstParamAdded(): bool
    {
        return $this->firstParamAdded;
    }

    /**
     * @param bool $firstParamAdded
     */
    public function setFirstParamAdded(bool $firstParamAdded): void
    {
        $this->firstParamAdded = $firstParamAdded;
    }

    /**
     * @return mixed
     */
    public function getFilterValueSeparator()
    {
        return $this->filterValueSeparator;
    }

    /**
     * @param mixed $filterValueSeparator
     */
    public function setFilterValueSeparator($filterValueSeparator): void
    {
        $this->filterValueSeparator = $filterValueSeparator;
    }

    /**
     * @return mixed
     */
    public function getFirstFilterValueSeparator()
    {
        return $this->firstFilterValueSeparator;
    }

    /**
     * @param mixed $firstFilterValueSeparator
     */
    public function setFirstFilterValueSeparator($firstFilterValueSeparator
    ): void
    {
        $this->firstFilterValueSeparator = $firstFilterValueSeparator;
    }

    /**
     * @return mixed
     */
    public function getFilterSeparator()
    {
        return $this->filterSeparator;
    }

    /**
     * @param mixed $filterSeparator
     */
    public function setFilterSeparator($filterSeparator): void
    {
        $this->filterSeparator = $filterSeparator;
    }

    /**
     * @return mixed
     */
    public function getFirstFilterSeparator()
    {
        return $this->firstFilterSeparator;
    }

    /**
     * @param mixed $firstFilterSeparator
     */
    public function setFirstFilterSeparator($firstFilterSeparator): void
    {
        $this->firstFilterSeparator = $firstFilterSeparator;
    }
}
