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

    public function __construct()
    {
        $this->shopCategories = new ArrayCollection();
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
}
