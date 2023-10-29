<?php

declare(strict_types=1);

namespace App\Service\RegisterUser;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Registers a new user.
 */
class RegisterUserService
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * //todo DTO? constraints
     *
     * @param array $data [
     *                      'password' => required ( @see User ),
     *                      'name' => required ( @see User ),
     *                      'surname' => required ( @see User ),
     *                      'email' => required ( @see User ),
     *                      'description' => not required ( @see User ),
     *                      'position' => required ( @see User ),
     *                      'positionDetails' => required ( @see User ),
     *                    ]
     *
     * @return User
     * @throws \DomainException
     */
    public function register(array $data): User
    {
        $user = new User();
        try {
            $user->setPassword($data['password']);
            $user->setName($data['name']);
            $user->setSurname($data['surname']);
            $user->setEmail($data['email']);
            $user->setRoles([User::ROLE_USER]);
            $user->setDescription($data['description'] ?? null);
            $user->setPosition($data['position']);
            $user->setPositionDetails($data['positionDetails']);
        } catch (\Throwable $throwable) {
            throw new \DomainException($throwable->getMessage());
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}
