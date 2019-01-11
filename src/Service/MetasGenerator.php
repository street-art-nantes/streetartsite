<?php

namespace App\Service;

use App\Entity\Artwork;
use App\Entity\Author;
use App\Model\OgMetas;
use App\Model\TwitterMetas;
use Contentful\Delivery\Resource\Entry;
use FOS\UserBundle\Model\UserInterface;
use Liip\ImagineBundle\Service\FilterService;
use Swift_Mailer as BaseMailer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Asset\Packages as AssetPackages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class MetasGenerator
{

    /**
     * MetasGenerator constructor.
     *
     */
    public function __construct()
    {

    }

    /**
     * @param $ressource mixed
     * @return OgMetas
     */
    public function getOgMetas($ressource)
    {
        $ogMetas = new OgMetas();

        if ($ressource instanceof Artwork) {
            $ogMetas->setDescription()
                ->setImage()
                ->setTitle()
                ->setType()
                ->setUrl();
        } elseif ($ressource instanceof Author) {
            $ogMetas->setDescription()
                ->setImage()
                ->setTitle()
                ->setType()
                ->setUrl();
        } elseif ($ressource instanceof Entry) {
            $ogMetas->setDescription()
                ->setImage()
                ->setTitle()
                ->setType()
                ->setUrl();
        }

        return $ogMetas;
    }

    /**
     * @param $ressource mixed
     * @return TwitterMetas
     */
    public function getTwitterMetas($ressource)
    {
        $twitterMetas = new TwitterMetas();

        if ($ressource instanceof Artwork) {
            $twitterMetas->setUrl()
                ->setTitle()
                ->setCard();
        } elseif ($ressource instanceof Author) {
            $twitterMetas->setUrl()
                ->setTitle()
                ->setCard();
        } elseif ($ressource instanceof Entry) {
            $twitterMetas->setUrl()
                ->setTitle()
                ->setCard();
        }

        return $twitterMetas;
    }

}
