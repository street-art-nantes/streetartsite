<?php

namespace App\Model;

class OgMetas
{

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $site_name = 'street-artwork.com';

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return OgMetas
     */
    public function setTitle(string $title): OgMetas
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return OgMetas
     */
    public function setType(string $type): OgMetas
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return OgMetas
     */
    public function setUrl(string $url): OgMetas
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return OgMetas
     */
    public function setImage(string $image): OgMetas
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return OgMetas
     */
    public function setDescription(string $description): OgMetas
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->site_name;
    }

    /**
     * @param string $site_name
     * @return OgMetas
     */
    public function setSiteName(string $site_name): OgMetas
    {
        $this->site_name = $site_name;
        return $this;
    }

}