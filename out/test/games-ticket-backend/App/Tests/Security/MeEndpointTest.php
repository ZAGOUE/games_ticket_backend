<?php

namespace App\Tests\Security;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MeEndpointTest extends WebTestCase
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $container = $this->client->getContainer(); // ✅ Important pour la couverture
        $this->em = $container->get(EntityManagerInterface::class);
        $this->hasher = $container->get(UserPasswordHasherInterface::class);
    }

    public function testAccessToProtectedRouteWithJwt(): void
    {
        $email = 'secure_user_'.uniqid().'@example.com';
        $plainPassword = 'Secure@123';

        // Supprime un utilisateur existant avec ce mail s’il y en a
        $existing = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existing) {
            $this->em->remove($existing);
            $this->em->flush();
        }

        // Création utilisateur
        $user = new User();
        $user->setEmail($email);
        $user->setFirstName('Secure');
        $user->setLastName('User');
        $user->setSecurityKey(Uuid::v4()->toRfc4122());
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, $plainPassword));

        $this->em->persist($user);
        $this->em->flush();

        // 🔐 Authentification
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => $email, 'password' => $plainPassword])
        );

        $this->assertResponseIsSuccessful();
        $content = $this->client->getResponse()->getContent();
        $data = json_decode($content, true);

        $this->assertArrayHasKey('token', $data);
        $token = $data['token'];

        // ✅ Appel à /api/me avec token
        $this->client->request(
            'GET',
            '/api/me',
            [],
            [],
            ['HTTP_Authorization' => 'Bearer '.$token]
        );

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($email, $response['email']);
    }
}
