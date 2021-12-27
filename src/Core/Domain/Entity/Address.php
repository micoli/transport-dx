<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Embeddable()
 */
final class Address implements JsonSerializable
{
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $display;

    public function __construct(
        string $address,
        ?string $display,
    ) {
        $this->address = $address;
        $this->display = $display;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function jsonSerialize(): mixed
    {
        return ['address' => $this->getAddress(), 'display' => $this->getDisplay()];
    }
}
