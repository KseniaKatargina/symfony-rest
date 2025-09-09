<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $this->em = $em;
        $this->hasher = $hasher;
    }

    public function createUser(array $data): User
    {
        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setPassword($this->hasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        $user->setName($data['name'] ?? $user->getName());
        if (!empty($data['password'])) {
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));
        }
        $this->em->flush();
        return $user;
    }

    public function deleteUser(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
