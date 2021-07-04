<?php

declare(strict_types=1);

namespace App\Core\Domain\Listener;

use App\Core\Repository\MessageRepository;
use App\Core\Service\MessageService;
use Micoli\Smtp\Server\Event\Events;
use Micoli\Smtp\Server\Event\MessageReceivedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class MessageSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private MessageRepository $messageRepository;
    private MessageService $messageService;

    public function __construct(
        LoggerInterface $logger,
        MessageRepository $messageRepository,
        MessageService $messageService,
    ) {
        $this->logger = $logger;
        $this->messageRepository = $messageRepository;
        $this->messageService = $messageService;
    }

    /**
     * @return array<string,string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Events::MESSAGE_RECEIVED => 'onMessageReceived',
        ];
    }

    public function onMessageReceived(MessageReceivedEvent $event): void
    {
        $this->logger->debug('Persisting message: '.$event->getMessage()->getId());
        $this->messageRepository->save($this->messageService->createFromSmtpMessage(
            $event->getMessage()->getRawContent()
        ));
    }
}
