<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PoiRepository")
 */
class Poi
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="point")
     */
    private $Coordinates;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $City;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Address;

    /**
     * @ORM\OneToMany(targetEntity="Oeuvre", mappedBy="poi")
     */
    private $oeuvres;

    public function __construct() {
        $this->oeuvres = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCoordinates()
    {
        return $this->Coordinates;
    }

    public function setCoordinates($Coordinates): self
    {
        $this->Coordinates = $Coordinates;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(string $Country): self
    {
        $this->Country = $Country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->City;
    }

    public function setCity(string $City): self
    {
        $this->City = $City;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOeuvres()
    {
        return $this->oeuvres;
    }

    /**
     * @param mixed $oeuvres
     * @return Poi
     */
    public function setOeuvres($oeuvres)
    {
        $this->oeuvres = $oeuvres;
        return $this;
    }
}
