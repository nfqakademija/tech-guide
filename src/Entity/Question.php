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

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->categories = new ArrayCollection();
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
     * @param int|null $followUpQuestion
     *
     * @return Question
     */
    public function setFollowUpQuestion(?int $followUpQuestion): self
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
     * @return array
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


}
