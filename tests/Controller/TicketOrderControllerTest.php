<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Offer;
use App\Entity\TicketOrder;
use DateTimeImmutable;

class TicketOrderControllerTest extends WebTestCase
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;
    private $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $this->em = $container->get(EntityManagerInterface::class);
        $this->hasher = $container->get(UserPasswordHasherInterface::class);
    }

    private function createUser(string $email, string $role): User
    {
        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) return $existingUser;

        $user = new User();
        $user->setEmail($email);
        $user->setFirstName('Test');
        $user->setLastName('User');
        $user->setSecurityKey(Uuid::v4()->toRfc4122());
        $user->setRoles([$role]);
        $user->setPassword($this->hasher->hashPassword($user, 'password123'));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    private function loginUser(string $email): string
    {
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => $email,
            'password' => 'password123'
        ]));

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        if (!isset($data['token'])) {
            $this->fail('Token non présent dans la réponse : ' . $response->getContent());
        }

        return $data['token'];
    }

    public function testCreateAndPayOrder(): void
    {
        $email = 'user_' . uniqid() . '@example.com';
        $user = $this->createUser($email, 'ROLE_USER');
        $token = $this->loginUser($email);

        // Crée une offre fictive avec les bons setters
        $offer = new Offer();
        $offer->setName('Test Offer');
        $offer->setDescription('Description');
        $offer->setPrice(99.99);
        $offer->setMaxPeople(100); // Note: doit être un int
        $offer->setCreatedAt(new DateTimeImmutable()); // Champ obligatoire

        $this->em->persist($offer);
        $this->em->flush();

        // Création d'une commande
        $this->client->request('POST', '/api/orders', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'offer_id' => $offer->getId(),
            'quantity' => 1
        ]));

        $this->assertResponseIsSuccessful();

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $orderId = $data['id'] ?? null;
        $this->assertNotNull($orderId, 'Order ID manquant dans la réponse.');

        // Paiement de la commande
        $this->client->request('POST', "/api/orders/{$orderId}/pay", [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testVerifyTicketAsController(): void
    {
        // Créer un utilisateur avec rôle CONTROLLER
        $email = 'controller_' . uniqid() . '@example.com';
        $this->createUser($email, 'ROLE_CONTROLLER');
        $token = $this->loginUser($email);

        // Créer une commande payée pour le test
        $offer = new Offer();
        $offer->setName('Test Offer');
        $offer->setDescription('Description');
        $offer->setPrice(99.99);
        $offer->setMaxPeople(100);
        $offer->setCreatedAt(new DateTimeImmutable());
        $this->em->persist($offer);

        $order = new TicketOrder();
        $order->setUser($this->createUser('test_user@example.com', 'ROLE_USER'));
        $order->setOffer($offer);
        $order->setQuantity(1);
        $order->setStatus('PAID');
        $order->setOrderKey(bin2hex(random_bytes(16)));
        $this->em->persist($order);
        $this->em->flush();

        $this->client->request('GET', '/api/orders/verify-ticket/' . $order->getOrderKey(), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);

        $this->assertResponseIsSuccessful();
    }
}