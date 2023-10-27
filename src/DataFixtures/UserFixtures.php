<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //todo więcej i bardziej sensowe fixtury

        $user = new User();
        $user->setPassword('admin');
        $user->setName('admin');
        $user->setSurname('admin');
        $user->setEmail('admin@fakeemail.fakeemail');
        $user->setRoles([User::ROLE_ADMIN]);
        $user->setDescription('admin');

        $manager->persist($user);
        $manager->flush();
    }
}
