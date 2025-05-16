<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UserTest extends TestCase
{
    public function testUserEntity(): void
    {
        $user = new User();

        $email = 'user@example.com';
        $firstName = 'John';
        $lastName = 'Doe';
        $roles = ['ROLE_USER'];
        $securityKey = Uuid::v4()->toRfc4122();
        $password = 'securePasswordHash';

        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRoles($roles);
        $user->setSecurityKey($securityKey);
        $user->setPassword($password);

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($roles, $user->getRoles());
        $this->assertEquals($securityKey, $user->getSecurityKey());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($email, $user->getUserIdentifier());
    }
}
