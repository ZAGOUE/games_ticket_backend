<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Entity\TicketOrder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginTest extends WebTestCase
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $this->em = $container->get(EntityManagerInterface::class);
        $this->hasher = $container->get(UserPasswordHasherInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->close();
    }

    public function testUserLogin(): void
    {
        $email = 'test_user@example.com';
        $plainPassword = 'Test@1234';

        // Cleanup - Supprime d'abord les commandes liées puis l'utilisateur
        $existing = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existing) {
            // Supprime toutes les commandes associées à cet utilisateur
            $orders = $this->em->getRepository(TicketOrder::class)
                ->findBy(['user' => $existing]);

            foreach ($orders as $order) {
                $this->em->remove($order);
            }

            $this->em->remove($existing);
            $this->em->flush();
        }

        // Create test user
        $user = new User();
        $user->setEmail($email);
        $user->setFirstName('Test');
        $user->setLastName('User');
        $user->setSecurityKey(Uuid::v4()->toRfc4122());
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, $plainPassword));

        $this->em->persist($user);
        $this->em->flush();

        // Test login
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => $email,
            'password' => $plainPassword
        ]));

        $this->assertResponseIsSuccessful();

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);
        $this->assertNotEmpty($data['token']);
    }

    public function testInvalidLogin(): void
    {
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]));

        $this->assertResponseStatusCodeSame(401);
    }
}