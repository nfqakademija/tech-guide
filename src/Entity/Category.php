<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
    private $categoryName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ShopCategory", mappedBy="category")
     */
    private $categoryShops;

    /**
     * @ORM\ManyToMany(targetEntity="Question", inversedBy="categories")
     * @ORM\JoinTable(name="category_question",
     *     joinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    private $questions;

    public function __construct()
    {
        $this->categoryShops = new ArrayCollection();
        $this->questions = new ArrayCollection();
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
    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    /**
     * @param string $categoryName
     *
     * @return Category
     */
    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCategoryShops() : Collection
    {
        return $this->categoryShops;
    }

    /**
     * @param Collection $categoryShops
     *
     * @return Category
     */
    public function setCategoryShops(Collection $categoryShops): self
    {
        $this->categoryShops = $categoryShops;

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
     * @param Collection $questions
     *
     * @return Category
     */
    public function setQuestions(array $questions): self
    {
        $this->questions = $questions;

        return $this;
    }


}
