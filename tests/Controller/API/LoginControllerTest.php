<?php

namespace App\Tests;

use App\Test\Traits\AuthenticationTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Class LoginControllerTest.
 */
class LoginControllerTest extends PantherTestCase
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

    public function testRetrieveLoggedUser(): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', '/api/users/me.json');

        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('city', $data);
    }

    public function testRetrieveUserOurself(): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', '/api/users/2.json');

        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('city', $data);
    }

    public function testRetrieveUserNotFound(): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', '/api/users/999.json');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testRetrieveOtherUserAccessDenied(): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', '/api/users/1.json');

        $this->assertResponseStatusCodeSame(403);
    }
}
