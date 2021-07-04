<?php

declare(strict_types=1);

namespace App\UserInterface\Web;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class IndexController
{
    private Environment $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @Route("/")
     * @Route("/app/{parameters}")
     */
    public function index(Request $request): Response
    {
        try {
            return new Response($this->environment->render('index.html.twig', [
                'count' => $request->attributes->get('count', 0),
            ]));
        } catch (InvalidArgumentException $exception) {
            if (preg_match('!Could not find the entrypoints file from Webpack!', $exception->getMessage())) {
                return new Response('Building assets');
            }
            throw $exception;
        }
    }
}
