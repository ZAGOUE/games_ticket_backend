<?php

namespace App\Tests\Entity;

use App\Entity\Offer;
use PHPUnit\Framework\TestCase;

class OfferTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $offer = new Offer();

        $offer->setId(1);
        $this->assertEquals(1, $offer->getId());

        $offer->setName('Nom de l\'offre');
        $this->assertEquals('Nom de l\'offre', $offer->getName());

        $offer->setDescription('Description test');
        $this->assertEquals('Description test', $offer->getDescription());

        $offer->setPrice(100.50);
        $this->assertEquals(100.50, $offer->getPrice());

        $offer->setMaxPeople(50);
        $this->assertEquals(50, $offer->getMaxPeople());

        $createdAt = new \DateTimeImmutable('2024-01-01');
        $offer->setCreatedAt($createdAt);
        $this->assertSame($createdAt, $offer->getCreatedAt());

        $updatedAt = new \DateTimeImmutable('2024-01-02');
        $offer->setUpdateAt($updatedAt);
        $this->assertSame($updatedAt, $offer->getUpdateAt());
    }
}
