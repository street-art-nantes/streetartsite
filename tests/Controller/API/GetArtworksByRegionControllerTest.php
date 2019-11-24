<?php

namespace App\Tests;

use App\Test\Traits\AuthenticationTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Class GetArtworksByRegionControllerTest.
 */
class GetArtworksByRegionControllerTest extends PantherTestCase
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

    public function testGetArtworksByRegion(): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', '/api/artworks.json?region[bounds]=-2.081161,17.006816,-1.161056,47.203', [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($client->getResponse()->getContent(), true);
        var_dump($data);

        $this->assertCount(5, $data);
    }

    public function testGetArtworksByRegionEmptyInput(): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', '/api/artworks.json?region[bounds]=', [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertResponseStatusCodeSame(500);
    }

    public function testGetArtworksByRegionWrongInput(): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', '/api/artworks.json?region[bounds]=XXX', [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertResponseStatusCodeSame(500);
    }
}
