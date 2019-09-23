<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PoiRepository")
 */
class Poi
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=6, nullable=true)
     * @Groups({"poi_read"})
     * @Assert\NotBlank()
     * @Assert\Regex("/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/")
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=6, nullable=true)
     * @Groups({"poi_read"})
     * @Assert\NotBlank()
     * @Assert\Regex("/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/")
     */
    private $longitude;

    /**
     * @ORM\Column(type="geometry", options={"geometry_type"="POINT"}, nullable=true)
     */
    private $point;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"poi_read"})
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"poi_read"})
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"poi_read"})
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"poi_read"})
     */
    private $highlight;

    /**
     * @ORM\OneToMany(targetEntity="Artwork", mappedBy="poi")
     */
    private $artworks;

    /**
     * Poi constructor.
     */
    public function __construct()
    {
        $this->artworks = new ArrayCollection();
        $this->highlight = false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return Poi
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return Poi
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return Poi
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getArtworks()
    {
        return $this->artworks;
    }

    /**
     * @param mixed $artworks
     *
     * @return Poi
     */
    public function setArtworks($artworks)
    {
        $this->artworks = $artworks;

        return $this;
    }

    protected function updatePoint()
    {
        $this->point = sprintf(
            'POINT(%f %f)',
            (string) $this->longitude,
            (string) $this->latitude
        );
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     *
     * @return Poi
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        $this->updatePoint();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     *
     * @return Poi
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        $this->updatePoint();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @param mixed $point
     *
     * @return Poi
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return sprintf('%s, %s;%s', $this->address, $this->getLatitude(), $this->getLongitude());
    }

    /**
     * @return mixed
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
     * @param mixed $highlight
     *
     * @return Poi
     */
    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;

        return $this;
    }
}
