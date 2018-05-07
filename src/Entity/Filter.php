<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilterRepository")
 */
class Filter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Regex", mappedBy="filter", orphanRemoval=true)
     */
    private $regexes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InfluenceArea", inversedBy="filters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $influenceArea;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Shop", inversedBy="filters")
     */
    private $shops;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $urlParameter;

    public function __construct()
    {
        $this->regexes = new ArrayCollection();
        $this->shops = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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
            $regex->setFilter($this);
        }

        return $this;
    }

    public function removeRegex(Regex $regex): self
    {
        if ($this->regexes->contains($regex)) {
            $this->regexes->removeElement($regex);
            // set the owning side to null (unless already changed)
            if ($regex->getFilter() === $this) {
                $regex->setFilter(null);
            }
        }

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
     * @return Collection|Shop[]
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    public function addShop(Shop $shop): self
    {
        if (!$this->shops->contains($shop)) {
            $this->shops[] = $shop;
        }

        return $this;
    }

    public function removeShop(Shop $shop): self
    {
        if ($this->shops->contains($shop)) {
            $this->shops->removeElement($shop);
        }

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
}
