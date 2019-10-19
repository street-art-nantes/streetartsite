<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageKit.
 */
class ImageKit
{
    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $env;

    /**
     * ImageKit constructor.
     *
     * @param string $privateKey
     */
    public function __construct(string $env, string $privateKey)
    {
        $this->env = $env;
        $this->privateKey = $privateKey;
    }

    /**
     * @param UploadedFile $file
     * @param string       $folder
     *
     * @return bool|mixed
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
                            'contents' => $file->getClientOriginalName(),
                        ],
                        [
                            'name' => 'folder',
                            'contents' => $folder.'_'.$this->env,
                        ],
                    ],
                ]
            );

            return json_decode($response->getBody()->getContents());
        } catch (ClientException $exception) {
            return false;
        }
    }
}
