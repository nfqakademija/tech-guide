<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $categoryFilter;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $html;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $htmlAddedAt;

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

    public function getCategoryFilter(): ?string
    {
        return $this->categoryFilter;
    }

    public function setCategoryFilter(?string $categoryFilter): self
    {
        $this->categoryFilter = $categoryFilter;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getHtmlAddedAt(): ?\DateTimeInterface
    {
        return $this->htmlAddedAt;
    }

    public function setHtmlAddedAt(?\DateTimeInterface $htmlAddedAt): self
    {
        $this->htmlAddedAt = $htmlAddedAt;

        return $this;
    }
}
