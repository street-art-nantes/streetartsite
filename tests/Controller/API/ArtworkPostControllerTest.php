<?php

namespace App\Tests;

use App\Test\Traits\AuthenticationTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Class ArtworkPostControllerTest.
 */
class ArtworkPostControllerTest extends PantherTestCase
{
    use ReloadDatabaseTrait;
    use AuthenticationTrait;

    /**
     * @var
     */
    private $authenticatedClient;

    public static function setUpBeforeClass(): void
    {
        self::$purgeWithTruncate = true;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getAuthenticatedClient()
    {
        if (!$this->authenticatedClient) {
            $this->authenticatedClient = $this->createAuthenticatedClient('thoma.vuille', 'p4ssWord');
        }

        return $this->authenticatedClient;
    }

    public function testPostArtwork(): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('POST', '/api/artworks.json', [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => 'Fresque Haguenau',
                'type' => 'graffiti',
                'poi' => [
                    'latitude' => '45.527245',
                    'longitude' => '-73.601608',
                    'country' => 'France',
                    'city' => 'Haguenau',
                    'address' => '34 rue des prés',
                    'highlight' => true,
                ],
                'documents' => [
                    [
                        'imageURL' => 'https://lorempixel.com/art/300/400',
                    ],
                ],
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('Fresque Haguenau', $data['title']);

        $artworkId = $data['id'];

        $client->request('GET', '/api/artworks/'.$artworkId.'.json');
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('Fresque Haguenau', $data['title']);
        $this->assertSame('graffiti', $data['type']);
    }
}
