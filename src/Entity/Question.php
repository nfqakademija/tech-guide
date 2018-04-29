<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
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
    private $priority;

    /**
     * @ORM\OneToOne(targetEntity="Question")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $followUpQuestion;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question")
     */
    private $answers;

    /**
     * @ORM\ManyToMany(targetEntity="Category", mappedBy="questions")
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="Shop", mappedBy="questions")
     */
    private $shops;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\InfluenceArea", mappedBy="questions")
     */
    private $influenceAreas;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->influenceAreas = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     *
     * @return Question
     */
    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFollowUpQuestion(): ?int
    {
        return $this->followUpQuestion;
    }

    /**
     * @param Question|null $followUpQuestion
     *
     * @return Question
     */
    public function setFollowUpQuestion(?Question $followUpQuestion): self
    {
        $this->followUpQuestion = $followUpQuestion;

        return $this;
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
     * @return Question
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAnswers() : Collection
    {
        return $this->answers;
    }

    /**
     * @param $answers
     *
     * @return Question
     */
    public function setAnswers($answers): self
    {
        $this->answers = $answers;

        return $this;
    }

    /**
     * @return Collection|InfluenceArea[]
     */
    public function getInfluenceAreas(): Collection
    {
        return $this->influenceAreas;
    }

    /**
     * @param InfluenceArea $influenceArea
     *
     * @return Question
     */
    public function addInfluenceArea(InfluenceArea $influenceArea): self
    {
        if (!$this->influenceAreas->contains($influenceArea)) {
            $this->influenceAreas[] = $influenceArea;
            $influenceArea->addQuestion($this);
        }

        return $this;
    }

    /**
     * @param InfluenceArea $influenceArea
     *
     * @return Question
     */
    public function removeInfluenceArea(InfluenceArea $influenceArea): self
    {
        if ($this->influenceAreas->contains($influenceArea)) {
            $this->influenceAreas->removeElement($influenceArea);
            $influenceArea->removeQuestion($this);
        }

        return $this;
    }
}
