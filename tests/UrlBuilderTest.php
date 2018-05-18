<?php

namespace App\Tests;

use App\Utils\UrlBuilder;
use PHPUnit\Framework\TestCase;

class UrlBuilderTest extends TestCase
{
    public function testAddFilter(): void
    {
        $urlBuilder = $this->createBuilder();
        $this->assertSame(
            'https://www.google.com/?filter1=1,2,3',
            $this->addFilter1($urlBuilder)->getUrl()
        );

        $this->assertSame(
            'https://www.google.com/?filter1=1,2,3;filter2=5',
            $this->addFilter2($urlBuilder)->getUrl()
        );

        $this->assertSame(
            'https://www.google.com/?filter1=1,2,3;filter2=5',
            $this->addFilter3($urlBuilder)->getUrl()
        );

        $urlBuilder = $this->createBuilder()->setRepeatingFilter(true);
        $this->assertSame(
            'https://www.google.com/?filter1=1;filter1=2;filter1=3',
            $this->addFilter1($urlBuilder)->getUrl()
        );

        $this->assertSame(
            'https://www.google.com/?filter1=1;filter1=2;filter1=3;filter2=5',
            $this->addFilter2($urlBuilder)->getUrl()
        );

        $this->assertSame(
            'https://www.google.com/?filter1=1;filter1=2;filter1=3;filter2=5',
            $this->addFilter3($urlBuilder)->getUrl()
        );
    }

    public function testRemoveFilter(): void
    {
        $urlBuilder = $this->addAllFilters($this->createBuilder());

        $this->assertSame(
            'https://www.google.com/?filter2=5',
            $this->removeFilter1($urlBuilder)->getUrl()
        );

        $this->assertSame(
            'https://www.google.com/?filter2=5',
            $this->removeFilter3($urlBuilder)->getUrl()
        );

        $this->assertSame(
            'https://www.google.com/?',
            $this->removeFilter2($urlBuilder)->getUrl()
        );

        $urlBuilder = $this->addAllFilters($this->createBuilder()->setRepeatingFilter(true));

        $this->assertSame(
            'https://www.google.com/?filter2=5',
            $this->removeFilter1($urlBuilder)->getUrl()
        );

        $this->assertSame(
            'https://www.google.com/?filter2=5',
            $this->removeFilter3($urlBuilder)->getUrl()
        );

        $this->assertSame(
            'https://www.google.com/?',
            $this->removeFilter2($urlBuilder)->getUrl()
        );
    }

    private function createBuilder() : UrlBuilder
    {
        return (new UrlBuilder())
            ->setUrl('https://www.google.com/')
            ->addFilterValueSeparators(',', '=')
            ->addFilterSeparators(';', '?');
    }

    private function addFilter1(UrlBuilder $urlBuilder) : UrlBuilder
    {
        return $urlBuilder->addFilter('filter1', [1, 2, 3]);
    }

    private function addFilter2(UrlBuilder $urlBuilder) : UrlBuilder
    {
        return $urlBuilder->addFilter('filter2', [5]);
    }

    private function addFilter3(UrlBuilder $urlBuilder) : UrlBuilder
    {
        return $urlBuilder->addFilter('filter3', []);
    }

    private function removeFilter1(UrlBuilder $urlBuilder) : UrlBuilder
    {
        $urlBuilder->removeFilter('filter1');

        return $urlBuilder;
    }

    private function removeFilter2(UrlBuilder $urlBuilder) : UrlBuilder
    {
        $urlBuilder->removeFilter('filter2');

        return $urlBuilder;
    }

    private function removeFilter3(UrlBuilder $urlBuilder) : UrlBuilder
    {
        $urlBuilder->removeFilter('filter3');

        return $urlBuilder;
    }

    private function addAllFilters(UrlBuilder $urlBuilder) : UrlBuilder
    {
        return $this->addFilter3(
            $this->addFilter2(
                $this->addFilter1($urlBuilder)
            )
        );
    }
}
