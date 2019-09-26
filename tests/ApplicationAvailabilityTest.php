<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/fr/map'];
        yield ['/fr/list'];
        yield ['/fr/artist-list'];
        yield ['/fr/blog'];
        yield ['/fr/artwork/new'];
        yield ['/fr/artist/new'];
        yield ['/fr/releases'];
        yield ['/fr/login'];
        yield ['/fr/faq'];

        yield ['/en/map'];
        yield ['/en/list'];
        yield ['/en/artist-list'];
        yield ['/en/blog'];
        yield ['/en/artwork/new'];
        yield ['/en/artist/new'];
        yield ['/en/releases'];
        yield ['/en/login'];
        yield ['/en/faq'];
    }
}
