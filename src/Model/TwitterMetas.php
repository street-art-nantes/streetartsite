<?php

namespace App\Model;

class TwitterMetas
{

    /**
     * @var string
     */
    protected $card = 'summary';

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $title;

    /**
     * @return string
     */
    public function getCard(): string
    {
        return $this->card;
    }

    /**
     * @param string $card
     * @return TwitterMetas
     */
    public function setCard(string $card): TwitterMetas
    {
        $this->card = $card;
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
     * @return TwitterMetas
     */
    public function setUrl(string $url): TwitterMetas
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return TwitterMetas
     */
    public function setTitle(string $title): TwitterMetas
    {
        $this->title = $title;
        return $this;
    }

}