<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HtmlRepository")
 */
class Html
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="text")
     */
    private $url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $addedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shop", inversedBy="htmls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FilterUsage", mappedBy="html", orphanRemoval=true)
     */
    private $filterUsages;

    public function __construct()
    {
        $this->addedAt = new \DateTime('now');
        $this->filterUsages = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeInterface $addedAt): self
    {
        $this->addedAt = $addedAt;

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
     * @return Collection|FilterUsage[]
     */
    public function getFilterUsages(): Collection
    {
        return $this->filterUsages;
    }

    public function addFilterUsage(FilterUsage $filterUsage): self
    {
        if (!$this->filterUsages->contains($filterUsage)) {
            $this->filterUsages[] = $filterUsage;
            $filterUsage->setHtml($this);
        }

        return $this;
    }

    public function removeFilterUsage(FilterUsage $filterUsage): self
    {
        if ($this->filterUsages->contains($filterUsage)) {
            $this->filterUsages->removeElement($filterUsage);
            // set the owning side to null (unless already changed)
            if ($filterUsage->getHtml() === $this) {
                $filterUsage->setHtml(null);
            }
        }

        return $this;
    }
}
