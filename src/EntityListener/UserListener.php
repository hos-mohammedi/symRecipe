<?php

namespace App\EntityListener;

use App\Entity\Users;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{

    public function __construct(private UserPasswordHasherInterface $hacher)
    {
    }

    public function prePersist(Users $users): void
    {
        $this->encodePassword($users);
    }

    public function preUpdate(Users $users): void
    {
        $this->encodePassword($users);
    }

    private function encodePassword(Users $users): void
    {
        if ($users->getPlainePassword() === null) {
            return;
        }

        $users->setPassword($this->hacher->hashPassword(
            $users,
            $users->getPlainePassword()
        ));
    }
}
