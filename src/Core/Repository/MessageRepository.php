<?php

declare(strict_types=1);

namespace App\Core\Repository;

use App\Core\Domain\Entity\Message;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Uuid;

final class MessageRepository
{
    /** @var EntityRepository<Message> */
    private EntityRepository $entityRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        /* @var EntityManager $entityManager */
        $this->entityRepository = $entityManager->getRepository(Message::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getAll(
        ?string $groupName,
    ): Collection {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select('m')
            ->from(Message::class, 'm')
            ->orderBy('m.date', 'DESC');

        if (!is_null($groupName)) {
            $queryBuilder
                ->where('m.mailGroup = :groupName')
                ->setParameter('groupName', $groupName);
        }

        return new ArrayCollection(
            $queryBuilder
                ->getQuery()
                ->getResult()
        );
    }

    public function getById(UUid $messageId): ?Message
    {
        /* @return ?Message $messageId */
        return $this->entityRepository->find($messageId);
    }

    /**
     * @return array{'mailGroup': string, 'numberOfMessage': int}
     */
    public function getGroups(): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('m.mailGroup', 'COUNT(m.id) as numberOfMessage', '(COUNT(m.id)-SUM(m.read)) as numberOfUnreadMessage')
            ->from(Message::class, 'm')
            ->groupBy('m.mailGroup')
            ->getQuery()
            ->getResult();
    }

    public function changeReadStatus(
        Uuid $messageId,
        bool $readStatus
    ): bool {
        /** @var ?Message $message */
        $message = $this->entityRepository->find($messageId);
        if (null === $message) {
            return false;
        }
        if ($readStatus) {
            $message->read();
        } else {
            $message->unRead();
        }
        $this->save($message);

        return true;
    }

    public function deleteMessagesByGroupName(string $groupName): int
    {
        return $this->entityManager->createQueryBuilder()
        ->delete('Domain:Message', 'm')
        ->where('m.mailGroup = :groupName')
        ->setParameter('groupName', $groupName)
        ->getQuery()
        ->getResult();
    }

    public function deleteMessageByMessageId(Uuid $messageId): int
    {
        return $this->entityManager->createQueryBuilder()
        ->delete('Domain:Message', 'm')
        ->where('m.id = :messageId')
        ->setParameter('messageId', $messageId, 'uuid')
        ->getQuery()
        ->getResult();
    }

    public function purge(): int
    {
        return $this->entityManager->createQueryBuilder()
        ->delete('Domain:Message', 'm')
        ->getQuery()
        ->getResult();
    }

    public function save(Message $message): void
    {
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        $this->entityManager->clear(Message::class);
    }
}
