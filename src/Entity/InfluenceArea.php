<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InfluenceAreaRepository")
 */
class InfluenceArea
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
    private $content;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Question", inversedBy="influenceAreas")
     * @ORM\OrderBy({"priority" = "ASC"})
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Filter", mappedBy="influenceArea", orphanRemoval=true)
     */
    private $filters;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->filters = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return InfluenceArea
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    /**
     * @param Question $question
     *
     * @return InfluenceArea
     */
    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
        }

        return $this;
    }

    /**
     * @param Question $question
     *
     * @return InfluenceArea
     */
    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
        }

        return $this;
    }

    /**
     * @param $questions
     *
     * @return InfluenceArea
     */
    public function setQuestions($questions): self
    {
        $this->questions = $questions;

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
            $filter->setInfluenceArea($this);
        }

        return $this;
    }

    public function removeFilter(Filter $filter): self
    {
        if ($this->filters->contains($filter)) {
            $this->filters->removeElement($filter);
            // set the owning side to null (unless already changed)
            if ($filter->getInfluenceArea() === $this) {
                $filter->setInfluenceArea(null);
            }
        }

        return $this;
    }
}
