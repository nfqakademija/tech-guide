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

    public function addHomePage(string $homepage) : self
    {
        $this->url .= $homepage;

        return $this;
    }

    public function addPrefix(string $prefix) : self
    {
        if($prefix[0] !== '/' && $this->url[strlen($this->url) - 1] !== '/') {
            $this->url .= '/';
        }
        $this->url .= $prefix;

        return $this;
    }

    public function addFilter(string $filter, string $value) : self
    {
        if($filter === null || $value === null) {
            return $this;
        }

        if(!$this->firstParamAdded) {
            if($this->url[strlen($this->url) - 1] !== '?') {
                $this->url .= '?';
                $this->firstParamAdded = true;
            }
        } else {
            if($this->url[strlen($this->url) - 1] !== '&') {
                $this->url .= '&';
            }
        }

        $this->url .= $filter;
        if($this->url[strlen($this->url) - 1] !== '=') {
            $this->url .= '=';
        }
        $this->url .= $value;

        return $this;
    }

    public function addFilterArray(array $filtersAndValues) : self
    {
        foreach ($filtersAndValues as $filterAndValue) {
            $this->addFilter($filterAndValue[0], $filterAndValue[1]);
        }

        return $this;
    }

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