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
    private $searchFilter;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $categoryFilter;

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
    public function getSearchFilter(): ?string
    {
        return $this->searchFilter;
    }

    /**
     * @param null|string $searchFilter
     *
     * @return ShopCategory
     */
    public function setSearchFilter(?string $searchFilter): self
    {
        $this->searchFilter = $searchFilter;

        return $this;
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
}
