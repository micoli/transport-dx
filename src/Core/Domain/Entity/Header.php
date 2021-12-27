<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Embeddable()
 */
final class Header implements JsonSerializable
{
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $key;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $value;

    public function __construct(
        string $key,
        string $value,
    ) {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): mixed
    {
        return ['key' => $this->getKey(), 'value' => $this->getValue()];
    }
}
