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
}
