<?php

namespace App\Core\Infrastructure\Persistence\DataFixtures;

use App\Core\Domain\Model\UniqueEmail;
use App\Core\Domain\Model\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        protected UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User(
            new UniqueEmail('lussoluca@example.com'),
            'password',
        );

        $user->updatePassword($this->passwordHasher->hashPassword($user, 'password'));
        $manager->persist($user);

        $manager->flush();
    }
}
