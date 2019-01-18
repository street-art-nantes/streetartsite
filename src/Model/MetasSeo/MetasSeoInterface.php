<?php

namespace App\Model\MetasSeo;

interface MetasSeoInterface
{

    const SITE_NAME = 'street-artwork.com';
    const DEFAULT_TWITTER_CARD = 'summary';
    const DEFAULT_OG_TYPE = 'website';

    public function getPageTitle();
    public function getPageUrl();
    public function getPageDescription();

    public function getOgType();
    public function getOgImage();

    public function getTwitterCard();

}