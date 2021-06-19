<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Kernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use React\Http\Server;
use React\Socket\Server as SocketServer;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Throwable;

final class HttpServer
{
    private Server $httpServer;

    public function __construct()
    {
        $httpFoundationFactory = new HttpFoundationFactory();
        $psr7Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr7Factory, $psr7Factory, $psr7Factory, $psr7Factory);
        $httpKernel = new Kernel('dev', true);
        $this->httpServer = new Server(function (ServerRequestInterface $request) use ($httpKernel, $httpFoundationFactory, $psrHttpFactory) {
            try {
                $httpKernel->incrementCount();
                $symfonyRequest = $httpFoundationFactory->createRequest($request);
                $symfonyRequest->attributes->set('count', $httpKernel->getCount());
                $response = $httpKernel->handle($symfonyRequest);
            } catch (Throwable $e) {
                return new Response(
                    500,
                    ['Content-Type' => 'text/plain'],
                    $e->getMessage()
                );
            }

            return $psrHttpFactory->createResponse($response);
        });
    }

    public function listen(SocketServer $socketServer): void
    {
        $this->httpServer->listen($socketServer);
    }
}
