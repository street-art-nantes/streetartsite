<?php

namespace App\Model\MetasSeo;

use Symfony\Component\Translation\TranslatorInterface;

class BlogMetasSeo implements MetasSeoInterface
{
    /**
     * @var mixed
     */
    private $entry;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * BlogMetasSeo constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function setEntry($entry)
    {
        $this->entry = $entry;
    }

    public function getPageTitle()
    {
        return $this->entry->get('title');
    }

    public function getPageDescription()
    {
        return $this->entry->get('introduction');
    }

    public function getOgType()
    {
        return MetasSeoInterface::DEFAULT_OG_TYPE;
    }

    public function getOgImage()
    {
        return $this->entry->get('introductionImage');
    }

    public function getTwitterCard()
    {
        return MetasSeoInterface::DEFAULT_TWITTER_CARD;
    }
}
