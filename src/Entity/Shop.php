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
     * @ORM\ManyToMany(targetEntity="Question", inversedBy="shops")
     * @ORM\JoinTable(name="shop_question",
     *     joinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    private $questions;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Filter", mappedBy="shops")
     */
    private $filters;

    public function __construct()
    {
        $this->shopCategories = new ArrayCollection();
        $this->filters = new ArrayCollection();
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

    /**
     * @return Collection
     */
    public function getQuestions() : Collection
    {
        return $this->questions;
    }

    /**
     * @param $questions
     *
     * @return Shop
     */
    public function setQuestions($questions): self
    {
        $this->questions = $questions;

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
     * @return Collection|Filter[]
     */
    public function getFilters(): Collection
    {
        return $this->filters;
    }

    public function addFilter(Filter $filter): self
    {
        if (!$this->filters->contains($filter)) {
            $this->filters[] = $filter;
            $filter->addShop($this);
        }

        return $this;
    }

    public function removeFilter(Filter $filter): self
    {
        if ($this->filters->contains($filter)) {
            $this->filters->removeElement($filter);
            $filter->removeShop($this);
        }

        return $this;
    }
}
