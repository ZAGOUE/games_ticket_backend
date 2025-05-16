<?php

namespace App\Tests\Repository;

use App\Entity\Offer;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\TicketOrder;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class TicketOrderRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testTicketOrderRepository(): void
    {
        $user = new User();
        $user->setEmail('order_user_' . uniqid() . '@example.com');
        $user->setFirstName('Ord');
        $user->setLastName('Er');
        $user->setRoles(['ROLE_USER']);
        $user->setSecurityKey(Uuid::v4());
        $user->setPassword('dummy');
        $this->em->persist($user);

        $offer = new Offer();
        $offer->setName('Special Event');
        $offer->setDescription('Special Ticket Event');
        $offer->setPrice(100);
        $offer->setMaxPeople(50);
        $offer->setCreatedAt(new \DateTimeImmutable());
        $this->em->persist($offer);

        $order = new TicketOrder();
        $order->setStatus('PAID');
        $order->setQuantity(1);
        $order->setOrderKey(Uuid::v4()->toRfc4122());
        $order->setUser($user);
        $order->setOffer($offer);
        $order->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($order);
        $this->em->flush();

        $results = $this->em->getRepository(TicketOrder::class)->countOrdersGroupedByOffer();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }

    public function testFindByStatus(): void
    {
        $repo = $this->em->getRepository(TicketOrder::class);

        $results = $repo->createQueryBuilder('t')
            ->where('t.status = :status')
            ->setParameter('status', 'PAID')
            ->getQuery()
            ->getResult();

        $this->assertIsArray($results);
    }

    public function testCountTotalOrders(): void
    {
        $repo = $this->em->getRepository(TicketOrder::class);

        $count = $repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertIsNumeric($count);
    }
}
