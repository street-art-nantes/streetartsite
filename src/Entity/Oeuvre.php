<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OeuvreRepository")
 */
class Oeuvre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $EndedAt;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $Tags;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\ManyToOne(targetEntity="Poi", inversedBy="oeuvres")
     */
    private $Poi;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="oeuvre")
     */
    private $Documents;

    /**
     * @ORM\OneToOne(targetEntity="Author", mappedBy="oeuvre")
     */
    private $Author;

    /**
     * Oeuvre constructor.
     */
    public function __construct() {
        $this->Documents = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->Status;
    }

    public function setStatus(bool $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->EndedAt;
    }

    public function setEndedAt(\DateTimeInterface $EndedAt): self
    {
        $this->EndedAt = $EndedAt;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->Tags;
    }

    public function setTags(?array $Tags): self
    {
        $this->Tags = $Tags;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoi()
    {
        return $this->Poi;
    }

    /**
     * @param mixed $Poi
     * @return Oeuvre
     */
    public function setPoi($Poi)
    {
        $this->Poi = $Poi;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->Documents;
    }

    /**
     * @param mixed $Documents
     * @return Oeuvre
     */
    public function setDocuments($Documents)
    {
        $this->Documents = $Documents;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->Author;
    }

    /**
     * @param mixed $Author
     * @return Oeuvre
     */
    public function setAuthor($Author)
    {
        $this->Author = $Author;
        return $this;
    }
}
