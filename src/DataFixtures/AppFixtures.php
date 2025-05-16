<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;

    public function __construct(UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator)
    {
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
    }

    public function load(ObjectManager $manager): void
    {
        echo "⚠️ Début du chargement des fixtures\n";

        // Création des utilisateurs
        $admin = new User();
        $admin->setEmail("admin@example.com");
        $admin->setFirstName("Admin");
        $admin->setLastName("Master");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setSecurityKey(Uuid::v4()->toRfc4122());
        $admin->setPassword($this->passwordHasher->hashPassword($admin, "Admin@123"));
        $errors = $this->validator->validate($admin);

        if (count($errors) > 0) {
            throw new \Exception((string) $errors);
        }
        echo "Ajout de l'utilisateur : " . $admin->getEmail() . PHP_EOL;

        $manager->persist($admin);

        $controller = new User();
        $controller->setEmail("controller@example.com");
        $controller->setFirstName("Control");
        $controller->setLastName("Manager");
        $controller->setRoles(["ROLE_CONTROLLER"]);
        $controller->setSecurityKey(Uuid::v4()->toRfc4122());
        $controller->setPassword($this->passwordHasher->hashPassword($controller, "Control@123"));
        $errors = $this->validator->validate($controller);
        if (count($errors) > 0) {
            throw new \Exception((string) $errors);
        }
        echo "Ajout de l'utilisateur : " . $controller->getEmail() . PHP_EOL;

        $manager->persist($controller);
        echo "✅ Fixtures terminées, on flush() maintenant\n";

        $user = new User();
        $user->setEmail("utilisateur@example.com");
        $user->setFirstName("John");
        $user->setLastName("Doe");
        $user->setRoles(["ROLE_USER"]);
        $user->setSecurityKey(Uuid::v4()->toRfc4122());
        $user->setPassword($this->passwordHasher->hashPassword($user, "User@123"));
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new \Exception((string) $errors);
        }
        echo "Ajout de l'utilisateur : " . $user->getEmail() . PHP_EOL;

        $manager->persist($user);


        // Création d'offres de test
        for ($i = 1; $i <= 5; $i++) {
            $offer = new Offer();
            $offer->setName("Offre $i");
            $offer->setDescription("Description de l'offre $i");
            $offer->setPrice(mt_rand(50, 200));
            $offer->setMaxPeople(mt_rand(10, 100));
            echo "Ajout de l'offre : " . $offer->getName() . PHP_EOL;

            $manager->persist($offer);
        }


        // Validation des changements
        echo "✅ Fixtures terminées";

        $manager->flush();

        $testUser = new User();
        $testUser->setEmail("testuser@example.com");
        $testUser->setFirstName("Test");
        $testUser->setLastName("User");
        $testUser->setRoles(["ROLE_USER"]);
        $testUser->setSecurityKey(Uuid::v4()->toRfc4122());
        $testUser->setPassword($this->passwordHasher->hashPassword($testUser, "Test@123"));
        $manager->persist($testUser);
    }

}

