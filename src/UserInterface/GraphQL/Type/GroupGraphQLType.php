<?php

declare(strict_types=1);

namespace App\UserInterface\GraphQL\Type;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @GQL\Type(name="Group")
 */
class GroupGraphQLType
{
    private ?string $name;
    private int $numberOfMessage;
    private int $numberOfUnreadMessage;

    public function __construct(
        ?string $name,
        int $numberOfMessage,
        int $numberOfUnreadMessage
    ) {
        $this->name = $name;
        $this->numberOfMessage = $numberOfMessage;
        $this->numberOfUnreadMessage = $numberOfUnreadMessage;
    }

    /**
     * @GQL\Field(type="String", name="name")
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @GQL\Field(type="Int", name="numberOfMessage")
     */
    public function getNumberOfMessage(): int
    {
        return $this->numberOfMessage;
    }

    /**
     * @GQL\Field(type="Int", name="numberOfUnreadMessage")
     */
    public function getNumberOfUnreadMessage(): int
    {
        return $this->numberOfUnreadMessage;
    }
}
