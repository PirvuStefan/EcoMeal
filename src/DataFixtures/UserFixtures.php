<?php

namespace App\DataFixtures;

use App\Entity\Business;
use App\Entity\Consumer;
use App\Entity\User;
use App\Repository\BusinessRepository;
use App\Repository\ConsumerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ConsumerRepository $consumerRepository,
        private readonly BusinessRepository $businessRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // --- User cu rol ROLE_CONSUMER, legat de un Consumer real ---
        $consumer = $this->consumerRepository->findAll()[0];

        $consumerUser = new User();
        $consumerUser->setEmail('consumer@example.com');
        $consumerUser->setRoles(['ROLE_CONSUMER']);
        $consumerUser->setPassword(
            $this->passwordHasher->hashPassword($consumerUser, 'password123')
        );
        $consumerUser->setConsumer($consumer);
        $manager->persist($consumerUser);

        $business = $this->businessRepository->findAll()[0];

        $businessUser = new User();
        $businessUser->setEmail('business@example.com');
        $businessUser->setRoles(['ROLE_BUSINESS']);
        $businessUser->setPassword(
            $this->passwordHasher->hashPassword($businessUser, 'password123')
        );
        $businessUser->setBusiness($business);
        $manager->persist($businessUser);

        $adminUser = new User();
        $adminUser->setEmail('admin@example.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword(
            $this->passwordHasher->hashPassword($adminUser, 'password123')
        );
        $manager->persist($adminUser);

        $manager->flush();
    }
}
