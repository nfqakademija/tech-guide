<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegexRepository")
 */
class Regex
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $htmlReducingRegex;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contentRegex;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $urlParameter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InfluenceArea", inversedBy="regexes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $influenceArea;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shop", inversedBy="regexes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="regexes")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHtmlReducingRegex(): ?string
    {
        return $this->htmlReducingRegex;
    }

    public function setHtmlReducingRegex(?string $htmlReducingRegex): self
    {
        $this->htmlReducingRegex = $htmlReducingRegex;

        return $this;
    }

    public function getContentRegex(): ?string
    {
        return $this->contentRegex;
    }

    public function setContentRegex(string $contentRegex): self
    {
        $this->contentRegex = $contentRegex;

        return $this;
    }

    public function getUrlParameter(): ?string
    {
        return $this->urlParameter;
    }

    public function setUrlParameter(string $urlParameter): self
    {
        $this->urlParameter = $urlParameter;

        return $this;
    }

    public function getInfluenceArea(): ?InfluenceArea
    {
        return $this->influenceArea;
    }

    public function setInfluenceArea(?InfluenceArea $influenceArea): self
    {
        $this->influenceArea = $influenceArea;

        return $this;
    }

    /**
     * @return Collection|ShopCategory[]
     */
    public function getShopCategories(): Collection
    {
        return $this->shopCategories;
    }

    public function addShopCategory(ShopCategory $shopCategory): self
    {
        if (!$this->shopCategories->contains($shopCategory)) {
            $this->shopCategories[] = $shopCategory;
        }

        return $this;
    }

    public function removeShopCategory(ShopCategory $shopCategory): self
    {
        if ($this->shopCategories->contains($shopCategory)) {
            $this->shopCategories->removeElement($shopCategory);
        }

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }
}
