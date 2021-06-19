<?php

declare(strict_types=1);

namespace App\UserInterface\GraphQL\Type;

use App\Core\Domain\Entity\Message;
use App\Core\Domain\Entity\Message as EntityMessage;
use App\Core\Repository\MessageRepository;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @GQL\Provider
 */
class MessageQuery
{
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @GQL\Query(type="[Message]", name="messages")
     *
     * @return array<MessageGraphQLType>
     */
    public function getMessages(): array
    {
        return $this->messageRepository
            ->getAll()
            ->map(fn (EntityMessage $message) => new MessageGraphQLType($message))
            ->toArray();
    }

    /**
     * @GQL\Query(type="Message", name="message")
     */
    public function getMessage(string $messageId): ?MessageGraphQLType
    {
        return new MessageGraphQLType($this->messageRepository->getById($messageId));
    }
}
