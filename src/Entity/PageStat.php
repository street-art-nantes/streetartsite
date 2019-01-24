<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageStatRepository")
 */
class PageStat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $views;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $path;

    /**
     * Author constructor.
     */
    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param mixed $views
     *
     * @return PageStat
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return PageStat
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
