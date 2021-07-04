<?php

declare(strict_types=1);

namespace App\UserInterface\GraphQL\Type;

use App\Core\Domain\Entity\Address;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @GQL\Type(name="Address")
 */
class AddressGraphQLType
{
    private Address $address;

    public function __construct(
        Address $address
    ) {
        $this->address = $address;
    }

    /**
     * @GQL\Field(type="String", name="address")
     */
    public function getAddress(): string
    {
        return $this->address->getAddress();
    }

    /**
     * @GQL\Field(type="String", name="display")
     */
    public function getDisplay(): ?string
    {
        return $this->address->getDisplay();
    }
}
