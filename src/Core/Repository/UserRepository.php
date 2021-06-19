<?php

declare(strict_types=1);

namespace App\Core\Repository;

use App\Core\Domain\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class UserRepository
{
    /** @var EntityRepository<User> */
    private EntityRepository $entityRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        /* @var EntityManager $entityManager */
        $this->entityRepository = $entityManager->getRepository(User::class);
        $this->entityManager = $entityManager;
    }

    public function getByUserName(string $username): ?User
    {
        /** @var ?User $user */
        $user = $this->entityRepository->findOneBy(['username' => $username]);

        return $user;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
