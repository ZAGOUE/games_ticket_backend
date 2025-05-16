<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Entity\Offer;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class RepositoryTests extends KernelTestCase
{
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testUserRepository(): void
    {
        $user = new User();
        $user->setEmail('repo_user_' . uniqid() . '@example.com');
        $user->setFirstName('Repo');
        $user->setLastName('User');
        $user->setRoles(['ROLE_USER']);
        $user->setSecurityKey(Uuid::v4());
        $user->setPassword('dummy');

        $this->em->persist($user);
        $this->em->flush();

        $found = $this->em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
        $this->assertNotNull($found);
        $this->assertEquals($user->getEmail(), $found->getEmail());
    }

    public function testOfferRepository(): void
    {
        $offer = new Offer();
        $offer->setName('Test Offer');
        $offer->setDescription('Description');
        $offer->setPrice(99.99);
        $offer->setMaxPeople(100);
        $offer->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($offer);
        $this->em->flush();

        $found = $this->em->getRepository(Offer::class)->findOneBy(['name' => 'Test Offer']);
        $this->assertNotNull($found);
        $this->assertEquals('Test Offer', $found->getName());
    }


}
