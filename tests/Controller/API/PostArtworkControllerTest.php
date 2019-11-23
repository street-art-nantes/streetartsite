<?php

namespace App\Tests;

use App\Test\Traits\AuthenticationTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Class PostArtworkControllerTest.
 */
class PostArtworkControllerTest extends PantherTestCase
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

    public function testStandardPostArtwork(): void
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
                        'imageKitData' => '{"filePath":"/cats/300/400","size":403460,"fileId":"5dbbfa98bffedf289db0d59a","url":"https://lorempixel.com/cats/300/400","name":"8E73BA22-5561-4064-8A3B-F0D7AAD5EC7A_2_uQ6zEnT.jpg","fileType":"image","thumbnailUrl":"https://lorempixel.com/cats/300/400","width":1120,"height":840}',
                    ],
                    [
                        'imageKitData' => '{"filePath":"/cats/300/400","size":403460,"fileId":"5dbbfa98bffedf289db0d59a","url":"https://lorempixel.com/cats/300/400","name":"8E73BA22-5561-4064-8A3B-F0D7AAD5EC7A_2_uQ6zEnT.jpg","fileType":"image","thumbnailUrl":"https://lorempixel.com/cats/300/400","width":1120,"height":840}',
                    ],
                    [
                        'imageKitData' => '{"filePath":"/cats/300/400","size":403460,"fileId":"5dbbfa98bffedf289db0d59a","url":"https://lorempixel.com/cats/300/400","name":"8E73BA22-5561-4064-8A3B-F0D7AAD5EC7A_2_uQ6zEnT.jpg","fileType":"image","thumbnailUrl":"https://lorempixel.com/cats/300/400","width":1120,"height":840}',
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
        var_dump($data);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('Fresque Haguenau', $data['title']);
        $this->assertSame('graffiti', $data['type']);
        $this->assertCount(3, $data['documents']);
        $this->assertNotNull($data['contributor']);

        foreach ($data['documents'] as $document) {
            $this->assertArrayHasKey('id', $document);
            $this->assertArrayHasKey('imageKitData', $document);
            $this->assertArrayHasKey('imageURI', $document);
        }
    }
}
