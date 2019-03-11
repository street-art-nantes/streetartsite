<?php

namespace App\Model;

class BadgesUser
{
    /**
     * @var int
     */
    private $artworkLevel;

    /**
     * @var int
     */
    private $artistLevel;

    /**
     * @var int
     */
    private $cityLevel;

    /**
     * @var int
     */
    private $countryLevel;

    /**
     * BadgesUser constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getArtworkLevel()
    {
        return $this->artworkLevel;
    }

    /**
     * @param int $artworkLevel
     *
     * @return BadgesUser
     */
    public function setArtworkLevel($artworkLevel): self
    {
        $this->artworkLevel = $artworkLevel;

        return $this;
    }

    /**
     * @return int
     */
    public function getArtistLevel()
    {
        return $this->artistLevel;
    }

    /**
     * @param int $artistLevel
     *
     * @return BadgesUser
     */
    public function setArtistLevel($artistLevel): self
    {
        $this->artistLevel = $artistLevel;

        return $this;
    }

    /**
     * @return int
     */
    public function getCityLevel()
    {
        return $this->cityLevel;
    }

    /**
     * @param int $cityLevel
     *
     * @return BadgesUser
     */
    public function setCityLevel($cityLevel): self
    {
        $this->cityLevel = $cityLevel;

        return $this;
    }

    /**
     * @return int
     */
    public function getCountryLevel()
    {
        return $this->countryLevel;
    }

    /**
     * @param int $countryLevel
     *
     * @return BadgesUser
     */
    public function setCountryLevel($countryLevel): self
    {
        $this->countryLevel = $countryLevel;

        return $this;
    }
}
