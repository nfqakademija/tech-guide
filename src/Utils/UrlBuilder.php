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
            if ($this->url[\strlen($this->url) - 1] !== '?') {
                $this->url .= '?';
                $this->firstParamAdded = true;
            }
        } else {
            if ($this->url[\strlen($this->url) - 1] !== '&') {
                $this->url .= '&';
            }
        }

        $this->url .= $filter;
        if ($this->url[\strlen($this->url) - 1] !== '=') {
            $this->url .= '=';
        }

        $and = '';
        foreach ($values as $value) {
            $this->url .= $and . $value;
            $and = '%2C';
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
        foreach ($filtersAndValues as $filterAndValue) {
            $this->addFilter($filterAndValue[0], $filterAndValue[1]);
        }

        return $this;
    }

    /**
     * @return UrlBuilder
     */
    public function reset() : self
    {
        $this->url = '';
        $this->firstParamAdded = false;

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
