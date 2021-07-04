<?php

declare(strict_types=1);

namespace App\UserInterface\GraphQL\Type;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @GQL\Type(name="Header")
 */
class HeaderGraphQLType
{
    private string $key;
    private string $value;

    public function __construct(
        string $key,
        string $value,
    ) {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @GQL\Field(type="String", name="key")
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @GQL\Field(type="String", name="value")
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
