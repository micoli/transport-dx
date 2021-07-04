<?php

declare(strict_types=1);

namespace App\UserInterface\Web;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AssetsController
{
    public function __construct()
    {
    }

    /**
     * @Route("/build/{asset}")
     */
    public function index(Request $request): Response
    {
        $publicResourcesFolderPath = __DIR__.'/../../../public/build/';
        $staticFile = $publicResourcesFolderPath.$request->get('asset');
        if (!file_exists($staticFile)) {
            throw new NotFoundHttpException();
        }

        return new BinaryFileResponse($staticFile);
    }
}
