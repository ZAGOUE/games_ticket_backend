<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;
    private $token;
    private $userId;
    private $email;
    private $password = 'Test@1234';

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->email = 'test.functional.' . uniqid() . '@example.com';

        // Créer un nouvel utilisateur
        $this->client->request('POST', '/api/users/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $this->email,
            'password' => $this->password
        ]));

        $this->assertResponseIsSuccessful();

        // Login
        $this->client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => $this->email,
            'password' => $this->password,
        ]));

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->token = $data['token'];

        // Get user ID via /api/me
        $this->client->request('GET', '/api/me', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token,
        ]);

        $this->assertResponseIsSuccessful();
        $me = json_decode($this->client->getResponse()->getContent(), true);
        $this->userId = $me['id'];
    }

    public function testRegisterNewUser(): void
    {
        $email = 'new_' . uniqid() . '@example.com';
        $this->client->request('POST', '/api/users/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'first_name' => 'New',
            'last_name' => 'User',
            'email' => $email,
            'password' => 'Test@1234'
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

    public function testGetUserById(): void
    {
        $this->client->request('GET', '/api/users/' . $this->userId, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUpdateUser(): void
    {
        $this->client->request('PUT', '/api/users/' . $this->userId, [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'Bearer ' . $this->token,
        ], json_encode([
            'first_name' => 'UpdatedFirst',
            'last_name' => 'UpdatedLast',
        ]));

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteUser(): void
    {
        $this->client->request('DELETE', '/api/users/' . $this->userId, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token,
        ]);

        $this->assertTrue(
            in_array($this->client->getResponse()->getStatusCode(), [200, 204]),
            "Expected status code 200 or 204, got " . $this->client->getResponse()->getStatusCode()
        );
    }
}
