<?php

namespace App\Test\Traits;

/**
 * Trait AuthenticationTrait.
 */
trait AuthenticationTrait
{
    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'komlan', $password = 'azerty')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
        $tokenData = json_decode(base64_decode(explode('.', $data['token'])[1], true));
        $client->userId = $tokenData->id;

        return $client;
    }
}
