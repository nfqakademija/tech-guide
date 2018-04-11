<?php

namespace App\Entity;

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

    public function __construct()
    {
        $this->shopCategories = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    public function setHomepage(string $homepage): self
    {
        $this->homepage = $homepage;

        return $this;
    }
}
