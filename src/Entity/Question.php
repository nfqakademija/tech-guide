<?php

namespace App\Entity;

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
    private $value;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question")
     */
    private $answers;

    /**
     * @ORM\ManyToMany(targetEntity="Category", mappedBy="questions")
     */
    private $categories;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getFollowUpQuestion(): ?int
    {
        return $this->followUpQuestion;
    }

    public function setFollowUpQuestion(?int $followUpQuestion): self
    {
        $this->followUpQuestion = $followUpQuestion;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
