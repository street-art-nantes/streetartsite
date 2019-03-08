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
     *
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getArtworkLevel(): int
    {
        return $this->artworkLevel;
    }

    /**
     * @param int $artworkLevel
     * @return BadgesUser
     */
    public function setArtworkLevel(int $artworkLevel): BadgesUser
    {
        $this->artworkLevel = $artworkLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getArtistLevel(): int
    {
        return $this->artistLevel;
    }

    /**
     * @param int $artistLevel
     * @return BadgesUser
     */
    public function setArtistLevel(int $artistLevel): BadgesUser
    {
        $this->artistLevel = $artistLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getCityLevel(): int
    {
        return $this->cityLevel;
    }

    /**
     * @param int $cityLevel
     * @return BadgesUser
     */
    public function setCityLevel(int $cityLevel): BadgesUser
    {
        $this->cityLevel = $cityLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountryLevel(): int
    {
        return $this->countryLevel;
    }

    /**
     * @param int $countryLevel
     * @return BadgesUser
     */
    public function setCountryLevel(int $countryLevel): BadgesUser
    {
        $this->countryLevel = $countryLevel;
        return $this;
    }
}
