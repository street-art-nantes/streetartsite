<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 */
class Author
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
    private $Name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Biography;

    /**
     * @ORM\OneToOne(targetEntity="Oeuvre", mappedBy="author")
     */
    private $Oeuvre;

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->Biography;
    }

    public function setBiography(?string $Biography): self
    {
        $this->Biography = $Biography;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOeuvre()
    {
        return $this->Oeuvre;
    }

    /**
     * @param mixed $Oeuvre
     * @return Author
     */
    public function setOeuvre($Oeuvre)
    {
        $this->Oeuvre = $Oeuvre;
        return $this;
    }
}
