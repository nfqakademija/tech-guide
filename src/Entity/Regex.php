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
     * @ORM\ManyToOne(targetEntity="App\Entity\Filter", inversedBy="regexes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $htmlReducingRegex;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contentRegex;

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

    public function getFilter(): ?Filter
    {
        return $this->filter;
    }

    public function setFilter(?Filter $filter): self
    {
        $this->filter = $filter;

        return $this;
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
