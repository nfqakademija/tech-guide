<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
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
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AnswerHistory", mappedBy="answers")
     */
    private $answerHistories;

    public function __construct()
    {
        $this->answerHistories = new ArrayCollection();
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
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Answer
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Question
     */
    public function getQuestion() : Question
    {
        return $this->question;
    }

    /**
     * @param Question $question
     *
     * @return Answer
     */
    public function setQuestion(Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return int
     */
    public function getValue() : int
    {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @return Answer
     */
    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|AnswerHistory[]
     */
    public function getAnswerHistories(): Collection
    {
        return $this->answerHistories;
    }

    public function addAnswerHistory(AnswerHistory $answerHistory): self
    {
        if (!$this->answerHistories->contains($answerHistory)) {
            $this->answerHistories[] = $answerHistory;
            $answerHistory->addAnswer($this);
        }

        return $this;
    }

    public function removeAnswerHistory(AnswerHistory $answerHistory): self
    {
        if ($this->answerHistories->contains($answerHistory)) {
            $this->answerHistories->removeElement($answerHistory);
            $answerHistory->removeAnswer($this);
        }

        return $this;
    }


}
