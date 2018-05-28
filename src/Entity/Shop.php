<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShopRepository")
 */
class Shop
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $homepage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ShopCategory", mappedBy="shop")
     */
    private $shopCategories;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $filterValueSeparator;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $firstFilterValueSeparator;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $firstFilterSeparator;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $filterSeparator;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Regex", mappedBy="shop", orphanRemoval=true)
     */
    private $regexes;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $logo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $repeatingFilter;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Html", mappedBy="shop", orphanRemoval=true)
     */
    private $htmls;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->shopCategories = new ArrayCollection();
        $this->regexes = new ArrayCollection();
        $this->htmls = new ArrayCollection();
    }

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
    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    /**
     * @param string $homepage
     *
     * @return Shop
     */
    public function setHomepage(string $homepage): self
    {
        $this->homepage = $homepage;

        return $this;
    }

    public function getFilterValueSeparator(): ?string
    {
        return $this->filterValueSeparator;
    }

    public function setFilterValueSeparator(string $filterValueSeparator): self
    {
        $this->filterValueSeparator = $filterValueSeparator;

        return $this;
    }

    public function getFirstFilterValueSeparator(): ?string
    {
        return $this->firstFilterValueSeparator;
    }

    public function setFirstFilterValueSeparator(?string $firstFilterValueSeparator): self
    {
        $this->firstFilterValueSeparator = $firstFilterValueSeparator;

        return $this;
    }

    public function getFirstFilterSeparator(): ?string
    {
        return $this->firstFilterSeparator;
    }

    public function setFirstFilterSeparator(?string $firstFilterSeparator): self
    {
        $this->firstFilterSeparator = $firstFilterSeparator;

        return $this;
    }

    public function getFilterSeparator(): ?string
    {
        return $this->filterSeparator;
    }

    public function setFilterSeparator(string $filterSeparator): self
    {
        $this->filterSeparator = $filterSeparator;

        return $this;
    }

    /**
     * @return Collection|Regex[]
     */
    public function getRegexes(): Collection
    {
        return $this->regexes;
    }

    public function addRegex(Regex $regex): self
    {
        if (!$this->regexes->contains($regex)) {
            $this->regexes[] = $regex;
            $regex->setShop($this);
        }

        return $this;
    }

    public function removeRegex(Regex $regex): self
    {
        if ($this->regexes->contains($regex)) {
            $this->regexes->removeElement($regex);
            // set the owning side to null (unless already changed)
            if ($regex->getShop() === $this) {
                $regex->setShop(null);
            }
        }

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getRepeatingFilter(): ?bool
    {
        return $this->repeatingFilter;
    }

    public function setRepeatingFilter(bool $repeatingFilter): self
    {
        $this->repeatingFilter = $repeatingFilter;

        return $this;
    }

    /**
     * @return Collection|Html[]
     */
    public function getHtmls(): Collection
    {
        return $this->htmls;
    }

    public function addHtml(Html $html): self
    {
        if (!$this->htmls->contains($html)) {
            $this->htmls[] = $html;
            $html->setShop($this);
        }

        return $this;
    }

    public function removeHtml(Html $html): self
    {
        if ($this->htmls->contains($html)) {
            $this->htmls->removeElement($html);
            // set the owning side to null (unless already changed)
            if ($html->getShop() === $this) {
                $html->setShop(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
