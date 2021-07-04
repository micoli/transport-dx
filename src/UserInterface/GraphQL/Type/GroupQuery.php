<?php

declare(strict_types=1);

namespace App\UserInterface\GraphQL\Type;

use App\Core\Repository\MessageRepository;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @GQL\Provider
 */
class GroupQuery
{
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @GQL\Query(type="[Group]", name="groups")
     *
     * @return array<GroupGraphQLType>
     */
    public function getGroups(): array
    {
        return array_map(
            fn (array $group) => new GroupGraphQLType($group['mailGroup'], $group['numberOfMessage']),
            $this->messageRepository
            ->getGroups()
        );
    }
}
