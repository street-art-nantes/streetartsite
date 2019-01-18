<?php

namespace App\Model\MetasSeo;

use App\Entity\Artwork;
use Symfony\Component\Translation\TranslatorInterface;

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
        $title = $this->translator->trans('title.artwork',[
            '%type%' => $this->artwork->getType(),
            '%id%' => $this->artwork->getId(),
            '%title%' => $this->artwork->getTitle(),
            '%artist%' => $this->artwork->getAuthor() ? '' : $this->artwork->getAuthor()->first()->getName(),
            '%ville%' => $this->artwork->getPoi()->getCity(),
            '%pays%' => $this->artwork->getPoi()->getCountry(),
        ], 'Metas');

        return $title;
    }

    public function getPageUrl()
    {
        //https://stackoverflow.com/questions/40492268/is-there-a-way-to-get-the-current-url-with-current-port-number-in-symfony2
        return 'pageurl';
    }
    public function getPageDescription()
    {
        return 'pagedescription';
    }

    public function getOgType()
    {
        return MetasSeoInterface::DEFAULT_OG_TYPE;
    }

    public function getOgImage()
    {
        return 'ogimage';
    }

    public function getTwitterCard()
    {
        return MetasSeoInterface::DEFAULT_TWITTER_CARD;
    }

}