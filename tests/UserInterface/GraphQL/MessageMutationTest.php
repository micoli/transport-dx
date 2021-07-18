<?php

declare(strict_types=1);

namespace App\Tests\UserInterface\GraphQL;

use App\Core\Domain\Entity\Message;
use App\Core\Repository\MessageRepository;
use App\Tests\AbstractIntegrationTest;

final class MessageMutationTest extends AbstractIntegrationTest
{
    private MessageRepository $messageRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->messageRepository = $this->getService(MessageRepository::class);
    }

    /**
     * @test
     */
    public function itShouldChangeReadStatusToRead()
    {
        $messages = $this->messageRepository->getAll(null);
        /** @var Message $message */
        $message = $messages->first();
        $graphqlResult = $this->graphqlRequest(sprintf(<<<EOG
          mutation changeReadStatus{
            changeReadStatus(messageId: "%s", isRead: true)
          }
        EOG, $message->getId()));
        self::assertSame(true, $graphqlResult['data']['changeReadStatus']);
        self::assertSame(true, $this->messageRepository->getById($message->getId())->isRead());
    }

    /**
     * @test
     */
    public function itShouldChangeReadStatusToUnread()
    {
        $messages = $this->messageRepository->getAll(null);
        /** @var Message $message */
        $message = $messages->first();
        $graphqlResult = $this->graphqlRequest(sprintf(<<<EOG
          mutation changeReadStatus{
            changeReadStatus(messageId: "%s", isRead: false)
          }
        EOG, $message->getId()));
        self::assertSame(true, $graphqlResult['data']['changeReadStatus']);
        self::assertSame(false, $this->messageRepository->getById($message->getId())->isRead());
    }

    /**
     * @test
     */
    public function itShouldDeleteAMessage()
    {
        $messages = $this->messageRepository->getAll(null);
        /** @var Message $message */
        $message = $messages->first();
        $graphqlResult = $this->graphqlRequest(sprintf(<<<EOG
          mutation deleteMessageById{
            deleteMessageById(messageId: "%s")
          }
        EOG, $message->getId()));
        self::assertSame(true, $graphqlResult['data']['deleteMessageById']);
        self::assertSame(null, $this->messageRepository->getById($message->getId()));
    }

    /**
     * @test
     */
    public function itShouldDeleteMessagesByGroup()
    {
        self::assertSame(4, $this->messageRepository->getAll(null)->count());
        self::assertSame(2, $this->messageRepository->getAll('group1')->count());
        $graphqlResult = $this->graphqlRequest(<<<EOG
          mutation deleteMessagesByGroup{
            deleteMessagesByGroup(groupName: "group1")
          }
        EOG);
        self::assertSame(true, $graphqlResult['data']['deleteMessagesByGroup']);
        self::assertSame(0, $this->messageRepository->getAll('group1')->count());
        self::assertSame(2, $this->messageRepository->getAll(null)->count());
        self::assertSame(1, $this->messageRepository->getAll('group2')->count());
    }

    /**
     * @test
     */
    public function itShouldPurgeMesages()
    {
        self::assertSame(4, $this->messageRepository->getAll(null)->count());
        $graphqlResult = $this->graphqlRequest(<<<EOG
          mutation purge{
            purge
          }
        EOG);
        self::assertSame(true, $graphqlResult['data']['purge']);
        self::assertSame(0, $this->messageRepository->getAll(null)->count());
    }
}
