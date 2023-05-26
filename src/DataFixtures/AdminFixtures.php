<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager)
    {
        $adminUser = new User();
        $adminUser->setEmail('admin@test.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setFirstName('Kristel');
        $adminUser->setLastName('OCAMPO');
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, 'admin123456'));

        $manager->persist($adminUser);
        $manager->flush();
    }
}
