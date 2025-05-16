<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthControllerTest extends WebTestCase
{
    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $em = $container->get(EntityManagerInterface::class);
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $email = 'login_' . uniqid() . '@example.com';
        $plainPassword = 'Test@123';

        $user = new User();
        $user->setEmail($email);
        $user->setFirstName('Test');
        $user->setLastName('Login');
        $user->setRoles(['ROLE_USER']);
        $user->setSecurityKey(Uuid::v4()->toRfc4122());
        $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
        $em->persist($user);
        $em->flush();

        $client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => $email,
            'password' => $plainPassword
        ]));

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);
        $this->assertNotEmpty($data['token']);
    }
}
