<?php

declare(strict_types=1);

namespace App\UserInterface\GraphQL\Type;

use App\Core\Repository\MessageRepository;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Uuid;

/**
 * @GQL\Provider
 */
class MessageMutation
{
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @GQL\Mutation()
     */
    public function changeReadStatus(string $messageId, bool $isRead): bool
    {
        return $this->messageRepository->changeReadStatus(Uuid::fromString($messageId), $isRead);
    }

    /**
     * @GQL\Mutation()
     */
    public function deleteMessagesByGroup(string $groupName): bool
    {
        return $this->messageRepository->deleteMessagesByGroupName($groupName) > 0;
    }

    /**
     * @GQL\Mutation()
     */
    public function purge(): bool
    {
        return $this->messageRepository->purge() > 0;
    }

    /**
     * @GQL\Mutation()
     */
    public function deleteMessageById(string $messageId): bool
    {
        return 1 === $this->messageRepository->deleteMessageByMessageId(Uuid::fromString($messageId));
    }
}
