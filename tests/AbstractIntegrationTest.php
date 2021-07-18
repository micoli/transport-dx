<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use Exception;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractIntegrationTest extends WebTestCase
{
    use MockeryPHPUnitIntegration;

    private ?EntityManager $entityManager = null;
    protected array $eventCalls = [];

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    protected function getService(string $serviceName): object
    {
        return self::$container->get($serviceName);
    }

    protected function getParameter(string $serviceName): mixed
    {
        return self::$container->getParameter($serviceName);
    }

    protected function setService(string $serviceId, object $service): void
    {
        self::$container->set($serviceId, $service);
    }

    protected function resetExpectedEvents(string $eventName): void
    {
        $this->eventCalls[$eventName] = [];
    }

    protected function traceEvent(string $eventName, string $eventClassname): void
    {
        $this->resetExpectedEvents($eventName);

        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->getService('event_dispatcher');
        $dispatcher->addListener($eventName, function ($event) use ($eventName) {
            $this->eventCalls[$eventName][] = $event;
        }, -1000000);
    }

    public function graphqlRequest(string $payload, array $headers = []): array
    {
        static::ensureKernelShutdown();

        $client = static::createClient();

        $client->request('POST', '/graphql/', ['query' => $payload], [], $headers);

        if (200 !== $client->getResponse()->getStatusCode()) {
            throw new Exception(sprintf('ResponseCode is %s: [%s]:%s', $client->getResponse()->getStatusCode(), $payload, $client->getInternalResponse()->getContent()));
        }

        return json_decode($client->getInternalResponse()->getContent(), true);
    }
}
