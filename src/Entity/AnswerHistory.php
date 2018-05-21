<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerHistoryRepository")
 */
class AnswerHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="answerHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Answer", inversedBy="answerHistories")
     */
    private $answers;

    /**
     * @ORM\Column(type="datetime")
     */
    private $addedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FilterUsage", mappedBy="answerHistory", orphanRemoval=true)
     */
    private $filterUsages;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $hash;

    public function __construct()
    {
        $this->addedAt = new \DateTime("now");
        $this->answers = new ArrayCollection();
        $this->filterUsages = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
        }

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
            $filterUsage->setAnswerHistory($this);
        }

        return $this;
    }

    public function removeFilterUsage(FilterUsage $filterUsage): self
    {
        if ($this->filterUsages->contains($filterUsage)) {
            $this->filterUsages->removeElement($filterUsage);
            // set the owning side to null (unless already changed)
            if ($filterUsage->getAnswerHistory() === $this) {
                $filterUsage->setAnswerHistory(null);
            }
        }

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }
}
