<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
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
    private $File;

    /**
     * @ORM\ManyToOne(targetEntity="Oeuvre", inversedBy="Documents")
     */
    private $Oeuvre;

    public function getId()
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->File;
    }

    public function setFile(string $File): self
    {
        $this->File = $File;

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
     * @return Document
     */
    public function setOeuvre($Oeuvre)
    {
        $this->Oeuvre = $Oeuvre;
        return $this;
    }
}
