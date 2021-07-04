<?php

declare(strict_types=1);

namespace App\Core\Fixture;

use App\Core\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(new User(
            'user1',
            'password1'
        ));
        $manager->flush();
    }
}
