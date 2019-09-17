<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageKit
 * @package App\Service
 */
class ImageKit
{
    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * ImageKit constructor.
     * @param string $publicKey
     * @param string $privateKey
     */
    public function __construct(string $publicKey, string $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    /**
     * @param UploadedFile $file
     * @param $folder
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload(UploadedFile $file, $folder)
    {
        $client = new Client();

        try {
            $response = $client->request(
                'POST',
                'https://api.imagekit.io/v1/files/upload',
                [
                    'auth' => [$this->privateKey, ''],
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => file_get_contents($file->getPathname()),
                            'filename' => $file->getClientOriginalName(),
                        ],
                        [
                            'name' => 'fileName',
                            'contents' => $file->getClientOriginalName()
                        ],
                        [
                            'name' => 'folder',
                            'contents' => $folder
                        ]
                    ]
                ]
            );

            return json_decode($response->getBody()->getContents());
        } catch (ClientException $exception) {
            return false;
        }
    }
}
