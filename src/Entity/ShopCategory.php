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

    public function getId(): int
    {
        return $this->id;
    }

    public function getSearchFilter(): ?string
    {
        return $this->searchFilter;
    }

    public function setSearchFilter(?string $searchFilter): self
    {
        $this->searchFilter = $searchFilter;

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

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }
}
