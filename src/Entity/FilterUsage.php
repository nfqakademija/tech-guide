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
     * @ORM\JoinColumn(nullable=true)
     */
    private $answerHistory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Html", inversedBy="filterUsages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $html;

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

    public function getHtml(): ?Html
    {
        return $this->html;
    }

    public function setHtml(?Html $html): self
    {
        $this->html = $html;

        return $this;
    }
}
