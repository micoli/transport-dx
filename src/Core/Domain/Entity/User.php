<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity
 */
final class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private UuidV4 $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $password;

    public function __construct(
        string $username,
        string $password,
    ) {
        $this->id = Uuid::v4();
        $this->username = $username;
        $this->password = $password;
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): string
    {
        return '';
    }
}
