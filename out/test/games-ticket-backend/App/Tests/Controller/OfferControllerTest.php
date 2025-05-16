<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferControllerTest extends WebTestCase
{
    private $client;
    private $token;
    private $offerId;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $email = 'admin_' . uniqid() . '@example.com';
        $password = 'Admin@1234';

        // Création de l'admin
        $this->client->request('POST', '/api/users/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'first_name' => 'Admin',
            'last_name' => 'Test',
            'email' => $email,
            'password' => $password,
            'roles' => ['ROLE_ADMIN']
        ]));

        // Connexion
        $this->client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => $email,
            'password' => $password
        ]));

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->token = $data['token'];

        // Création de l'offre
        $this->client->request('POST', '/api/offers', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'Test Offer',
            'description' => 'Test Description',
            'price' => 50,
            'max_people' => 100,
        ]));

        $this->assertResponseIsSuccessful();
        $offer = json_decode($this->client->getResponse()->getContent(), true);
        $this->offerId = $offer['id'] ?? null;
        $this->assertNotNull($this->offerId);
    }

    public function testGetOfferById(): void
    {
        $this->client->request('GET', '/api/offers/' . $this->offerId, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateOffer(): void
    {
        $this->client->request('PUT', '/api/offers/' . $this->offerId, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'Updated Offer Title',
            'description' => 'Updated description',
            'price' => 90,
            'max_people' => 250,
        ]));

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteOffer(): void
    {
        $this->client->request('DELETE', '/api/offers/' . $this->offerId, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token,
        ]);

        $this->assertTrue(
            in_array($this->client->getResponse()->getStatusCode(), [200, 204]),
            'Expected 200 or 204 but got ' . $this->client->getResponse()->getStatusCode()
        );
    }
}
