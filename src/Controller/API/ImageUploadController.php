<?php

namespace App\Controller\API;

use App\Service\ImageKit;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class ImageUploadController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ImageKit
     */
    private $imageKit;

    /**
     * ImageUploadController constructor.
     *
     * @param ImageKit            $imageKit
     * @param TranslatorInterface $translator
     * @param LoggerInterface     $logger
     */
    public function __construct(ImageKit $imageKit, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $this->imageKit = $imageKit;
        $this->translator = $translator;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        if (!$request->files->has('file')) {
            throw $this->createNotFoundException();
        }

        $file = $request->files->get('file');

        $errors = [];
        $response = null;

        try {
            $response = $this->imageKit->upload($file, 'artworks-images');
        } catch (\Exception $exception) {
            $errors[] = $exception->getMessage();
        }

        return new JsonResponse([
            'error' => [],
            'data' => $response,
        ]);
    }
}
