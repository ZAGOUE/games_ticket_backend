<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class ProtectedEndpointTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $container = static::getContainer();
        $this->em = $container->get(EntityManagerInterface::class);
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Désactive le reboot du kernel
        $this->client->disableReboot();

        // Démarre une transaction
        $this->em->getConnection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        // Annule la transaction
        if ($this->em->getConnection()->isTransactionActive()) {
            $this->em->getConnection()->rollBack();
        }

        parent::tearDown();
    }

    public function testProtectedEndpoint(): void
    {
        // Crée un utilisateur unique avec tous les champs requis
        $email = 'test_'.uniqid().'@example.com';
        $password = 'Test@1234';

        $user = new User();
        $user->setEmail($email);
        $user->setFirstName('Test');
        $user->setLastName('User');
        $user->setSecurityKey(Uuid::v4()->toRfc4122());
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();
        $this->em->clear();

        // Login
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $email,
                'password' => $password
            ])
        );

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $response);
        $token = $response['token'];

        // Appelle un vrai endpoint protégé
        $this->client->request(
            'GET',
            '/api/me',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$token]
        );

        $this->assertResponseIsSuccessful();
    }
}
