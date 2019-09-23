<?php

namespace App\Model\MetasSeo;

use App\Entity\Author;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthorMetasSeo implements MetasSeoInterface
{
    /**
     * @var Author
     */
    private $author;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $locale;

    /**
     * AuthorMetasSeo constructor.
     *
     * @param TranslatorInterface $translator
     * @param string              $locale
     */
    public function __construct(TranslatorInterface $translator, string $locale)
    {
        $this->translator = $translator;
        $this->locale = $locale;
    }

    public function setAuthor(Author $author)
    {
        $this->author = $author;
    }

    public function getPageTitle()
    {
        $title = $this->translator->trans('title.author', [
            '%name%' => $this->author->getName(),
        ], 'Metas');

        return $title;
    }

    public function getPageDescription()
    {
        $description = $this->translator->trans('description.author', [
            '%name%' => $this->author->getName(),
            '%biobdd%' => ('fr' === $this->locale) ?
                mb_substr($this->author->getBiography(), 0, 80) :
                mb_substr($this->author->getBiographyEn(), 0, 80),
        ], 'Metas');

        return $description;
    }

    public function getOgType()
    {
        return MetasSeoInterface::DEFAULT_OG_TYPE;
    }

    public function getOgImage()
    {
        return $this->author->getAvatarFile();
    }

    public function getTwitterCard()
    {
        return MetasSeoInterface::DEFAULT_TWITTER_CARD;
    }
}
