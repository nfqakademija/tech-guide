<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GuidebotSentenceRepository")
 */
class GuidebotSentence
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
    private $sentence;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $purpose;

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
    public function getSentence(): ?string
    {
        return $this->sentence;
    }

    /**
     * @param string $sentence
     *
     * @return GuidebotSentence
     */
    public function setSentence(string $sentence): self
    {
        $this->sentence = $sentence;

        return $this;
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
     * @return GuidebotSentence
     */
    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     *
     * @return GuidebotSentence
     */
    public function setPurpose(string $purpose): self
    {
        $this->purpose = $purpose;

        return $this;
    }
}
