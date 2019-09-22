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
//        yield ['/fr/list'];
        yield ['/fr/artist-list'];
        yield ['/fr/blog'];
        yield ['/fr/artwork/new'];
        yield ['/fr/artist/new'];
    }
}
