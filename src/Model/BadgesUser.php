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
     * @var int
     */
    private $instaLevel;

    /**
     * @var int
     */
    private $hunterProfileLevel;

    /**
     * @var int
     */
    private $hunterArtworkLevel;

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

    /**
     * @return int
     */
    public function getInstaLevel()
    {
        return $this->instaLevel;
    }

    /**
     * @param int $instaLevel
     * @return BadgesUser
     */
    public function setInstaLevel($instaLevel): self
    {
        $this->instaLevel = $instaLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getHunterProfileLevel()
    {
        return $this->hunterProfileLevel;
    }

    /**
     * @param int $hunterProfileLevel
     * @return BadgesUser
     */
    public function setHunterProfileLevel($hunterProfileLevel): self
    {
        $this->hunterProfileLevel = $hunterProfileLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getHunterArtworkLevel()
    {
        return $this->hunterArtworkLevel;
    }

    /**
     * @param int $hunterArtworkLevel
     * @return BadgesUser
     */
    public function setHunterArtworkLevel($hunterArtworkLevel): self
    {
        $this->hunterArtworkLevel = $hunterArtworkLevel;
        return $this;
    }
}
