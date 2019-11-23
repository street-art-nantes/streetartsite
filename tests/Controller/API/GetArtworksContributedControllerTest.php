<?php

namespace App\Tests;

use App\Test\Traits\AuthenticationTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Class GetArtworksContributedControllerTest.
 */
class GetArtworksContributedControllerTest extends PantherTestCase
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

    public function testGetArtworksContributedByMe(): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', '/api/artworks.json?contributor.id='.$client->userId, [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(2, $data);
    }

    public function testGetArtworksContributedByUser3(): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', '/api/artworks.json?contributor.id=3', [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(4, $data);
    }
}
