<?php

namespace App\Tests\Entity;

use App\Entity\Payment;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $payment = new Payment();

        $payment->setId(1);
        $payment->setOrderId(123);
        $payment->setAmount(99.99);
        $payment->setPaymentStatus('PAID');

        $createdAt = new \DateTimeImmutable('now');
        $payment->setCreatedAt($createdAt);

        $this->assertEquals(1, $payment->getId());
        $this->assertEquals(123, $payment->getOrderId());
        $this->assertEquals(99.99, $payment->getAmount());
        $this->assertEquals('PAID', $payment->getPaymentStatus());
        $this->assertEquals($createdAt, $payment->getCreatedAt());
    }
}
