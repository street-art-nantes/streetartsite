<?php

namespace App\Model\MetasSeo;

use App\Entity\Artwork;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArtworkMetasSeo implements MetasSeoInterface
{
    /**
     * @var Artwork
     */
    private $artwork;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ArtworkMetasSeo constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function setArtwork(Artwork $artwork)
    {
        $this->artwork = $artwork;
    }

    public function getPageTitle()
    {
        $author = $this->artwork->getAuthor()->first() ? $this->artwork->getAuthor()->first()->getName() : '';
        $title = $this->translator->transChoice('title.artwork', \mb_strlen($author), [
            '%type%' => ucfirst($this->artwork->getType()),
            '%id%' => $this->artwork->getId(),
            '%title%' => $this->artwork->getTitle(),
            '%artist%' => $author,
            '%ville%' => $this->artwork->getPoi()->getCity(),
            '%pays%' => $this->artwork->getPoi()->getCountry(),
        ], 'Metas');

        return $title;
    }

    public function getPageDescription()
    {
        $author = $this->artwork->getAuthor()->first() ? $this->artwork->getAuthor()->first()->getName() : '';
        $description = $this->translator->transChoice('description.artwork', \mb_strlen($author), [
            '%type%' => ucfirst($this->artwork->getType()),
            '%id%' => $this->artwork->getId(),
            '%title%' => $this->artwork->getTitle(),
            '%artist%' => $author,
            '%ville%' => $this->artwork->getPoi()->getCity(),
            '%pays%' => $this->artwork->getPoi()->getCountry(),
        ], 'Metas');

        return $description;
    }

    public function getOgType()
    {
        return MetasSeoInterface::DEFAULT_OG_TYPE;
    }

    public function getOgImage()
    {
        return $this->artwork->getDocuments()->first->getImage();
    }

    public function getTwitterCard()
    {
        return MetasSeoInterface::DEFAULT_TWITTER_CARD;
    }
}
