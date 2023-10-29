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
        $this->createAdmin($manager);
        $this->createUsersPositionTester($manager, 10);

        $manager->flush();
    }

    private function createAdmin(ObjectManager $manager): void
    {
        $user = new User();
        $user->setPassword('admin');
        $user->setName('adminName');
        $user->setSurname('adminSurname');
        $user->setEmail('admin@fakeEmail.fakeEmail');
        $user->setRoles([User::ROLE_ADMIN]);
        $user->setDescription('admin');

        $manager->persist($user);
    }

    private function createUsersPositionTester(ObjectManager $manager, int $numberOfUsers = 1): void
    {
        for ($i = 1; $i <= abs($numberOfUsers); $i++) {
            $user = new User();
            $user->setPassword('user' . $i);
            $user->setName('userName' . $i);
            $user->setSurname('userSurname' . $i);
            $user->setEmail('user' . $i . '@fakeEmail.fakeEmail');
            $user->setRoles([User::ROLE_USER]);
            $user->setDescription('user' . $i);
            $user->setPosition(User::POSITION_TESTER);
            $user->setPositionDetails(['user' . $i => 'details' . $i]);

            $manager->persist($user);
        }
    }
}
