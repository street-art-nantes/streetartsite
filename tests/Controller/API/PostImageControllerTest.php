<?php

namespace App\Tests;

use App\Test\Traits\AuthenticationTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Class PostImageControllerTest.
 */
class PostImageControllerTest extends PantherTestCase
{
    use ReloadDatabaseTrait;
    use AuthenticationTrait;

    public static function setUpBeforeClass(): void
    {
        self::$purgeWithTruncate = true;
    }

    public function testPostAndDeleteImage(): void
    {
        // Create new image
        $clientUser = $this->createAuthenticatedClient('thoma.vuille', 'p4ssWord');
        $container = self::$container;

        $imageFile = new UploadedFile($container->getParameter('kernel.project_dir').'/tests/Resources/arbres-herons.jpg', 'photo-test.jpg', 'image/jpeg', 123);

        $clientUser->request('POST',
            '/api/images',
            ['fileName' => 'Fabien'],
            ['file' => $imageFile]
        );

        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($clientUser->getResponse()->getContent(), true);

        $fileId = $data['fileId'];

        // Remove file in imagekit
        $client = new \GuzzleHttp\Client();

        $response = $client->request(
            'DELETE',
            'https://api.imagekit.io/v1/files/'.$fileId,
            [
                'auth' => [$container->getParameter('imagekit_private_key'), ''],
            ]
        );

        $this->assertSame(204, $response->getStatusCode());
    }
}
