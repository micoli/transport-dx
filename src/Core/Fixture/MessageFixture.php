<?php

declare(strict_types=1);

namespace App\Core\Fixture;

use App\Core\Domain\Entity\Address;
use App\Core\Domain\Entity\Message;
use App\Core\Repository\MessageRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class MessageFixture extends Fixture
{
    private MessageRepository $messageRepository;

    public function __construct(
        MessageRepository $messageRepository,
    ) {
        $this->messageRepository = $messageRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $manager->flush();
        foreach ($this->getMessages() as $message) {
            $this->messageRepository->save($message);
        }
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return [
            new Message(
                new DateTimeImmutable('2021-12-03 12:10:00'),
                new Address('from1@foo.com', null),
                'subject 1',
                [new Address('to1@foo.com', null), new Address('to2@foo.com', 'TO')],
                [],
                [],
                [],
                [],
                'message 1',
                null,
                '',
                ''
            ),
            new Message(
                new DateTimeImmutable('2021-12-13 12:10:00'),
                new Address('from1@foo.com', null),
                'subject 2',
                [new Address('to2@foo.com', null)],
                [],
                [],
                [],
                [],
                'message 2',
                null,
                '',
                'group1'
            ),
            new Message(
                new DateTimeImmutable('2021-12-03 12:10:00'),
                new Address('from3@foo.com', null),
                'subject 3',
                [new Address('to1@foo.com', null), new Address('to2@foo.com', 'TO')],
                [],
                [],
                [],
                [],
                'message 3',
                null,
                '',
                'group1'
            ),
            new Message(
                new DateTimeImmutable('2021-12-03 12:10:00'),
                new Address('from4@foo.com', null),
                'subject 4',
                [new Address('to4@foo.com', null), new Address('to2@foo.com', 'TO')],
                [],
                [],
                [],
                [],
                'message 4',
                null,
                '',
                'group2'
            ),
        ];
    }
}
