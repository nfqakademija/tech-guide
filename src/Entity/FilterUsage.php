<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilterUsageRepository")
 */
class FilterUsage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnswerHistory", inversedBy="filterUsages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $answerHistory;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Html", inversedBy="filterUsages")
     */
    private $htmls;

    public function __construct()
    {
        $this->htmls = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getAnswerHistory(): ?AnswerHistory
    {
        return $this->answerHistory;
    }

    public function setAnswerHistory(?AnswerHistory $answerHistory): self
    {
        $this->answerHistory = $answerHistory;

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
        }

        return $this;
    }

    public function removeHtml(Html $html): self
    {
        if ($this->htmls->contains($html)) {
            $this->htmls->removeElement($html);
        }

        return $this;
    }
}
