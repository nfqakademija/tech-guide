<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShopCategoryRepository")
 */
class ShopCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $categoryFilter;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $colorFilter;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $priceFilter;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $memoryFilter;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $prefix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shop", inversedBy="shopCategories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="categoryShops")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ssdFilter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hddFilter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $processorFilter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ramFilter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sizeFilter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resolutionFilter;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return null|string
     */
    public function getCategoryFilter(): ?string
    {
        return $this->categoryFilter;
    }

    /**
     * @param null|string $categoryFilter
     *
     * @return ShopCategory
     */
    public function setCategoryFilter(?string $categoryFilter): self
    {
        $this->categoryFilter = $categoryFilter;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @param null|string $prefix
     *
     * @return ShopCategory
     */
    public function setPrefix(?string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getColorFilter() : ?string
    {
        return $this->colorFilter;
    }

    /**
     * @param string $colorFilter
     *
     * @return ShopCategory
     */
    public function setColorFilter(string $colorFilter): self
    {
        $this->colorFilter = $colorFilter;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPriceFilter() : ?string
    {
        return $this->priceFilter;
    }

    /**
     * @param string $priceFilter
     *
     * @return ShopCategory
     */
    public function setPriceFilter(string $priceFilter): self
    {
        $this->priceFilter = $priceFilter;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMemoryFilter() : ?string
    {
        return $this->memoryFilter;
    }

    /**
     * @param string $memoryFilter
     *
     * @return ShopCategory
     */
    public function setMemoryFilter(string $memoryFilter): self
    {
        $this->memoryFilter = $memoryFilter;

        return $this;
    }

    /**
     * @return Shop
     */
    public function getShop() : Shop
    {
        return $this->shop;
    }

    /**
     * @param Shop $shop
     *
     * @return ShopCategory
     */
    public function setShop(Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory() : Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return ShopCategory
     */
    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSsdFilter(): ?string
    {
        return $this->ssdFilter;
    }

    public function setSsdFilter(?string $ssdFilter): self
    {
        $this->ssdFilter = $ssdFilter;

        return $this;
    }

    public function getHddFilter(): ?string
    {
        return $this->hddFilter;
    }

    public function setHddFilter(?string $hddFilter): self
    {
        $this->hddFilter = $hddFilter;

        return $this;
    }

    public function getProcessorFilter(): ?string
    {
        return $this->processorFilter;
    }

    public function setProcessorFilter(?string $processorFilter): self
    {
        $this->processorFilter = $processorFilter;

        return $this;
    }

    public function getRamFilter(): ?string
    {
        return $this->ramFilter;
    }

    public function setRamFilter(?string $ramFilter): self
    {
        $this->ramFilter = $ramFilter;

        return $this;
    }

    public function getSizeFilter(): ?string
    {
        return $this->sizeFilter;
    }

    public function setSizeFilter(?string $sizeFilter): self
    {
        $this->sizeFilter = $sizeFilter;

        return $this;
    }

    public function getResolutionFilter(): ?string
    {
        return $this->resolutionFilter;
    }

    public function setResolutionFilter(?string $resolutionFilter): self
    {
        $this->resolutionFilter = $resolutionFilter;

        return $this;
    }
}
