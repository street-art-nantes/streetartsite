<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class E2eTest extends PantherTestCase
{
    public function testMyApp(): void
    {
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $client->request('GET', '/');

        // Use any PHPUnit assertion, including the ones provided by Symfony
        $this->assertPageTitleContains('Site de streetart');
//        $this->assertSelectorTextContains('#main', 'My body');
    }
}
