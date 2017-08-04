<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Nimbles\OnlineBetaalPlatform\Model;

use PHPUnit\Framework\TestCase;

/**
 * Class PaymentTest
 */
class PaymentTest extends TestCase
{
    public function testPayment()
    {
        $payment = new Payment('https://nimbles.com/return/url', 1050);

        $this->assertEquals(1050, $payment->getAmount());
        $this->assertEquals('https://nimbles.com/return/url', $payment->getReturnUrl());

        $payment->setUid('secret-uuid');
        $this->assertEquals('secret-uuid', $payment->getUid());

        $payment->setStatus('created');
        $this->assertEquals('created', $payment->getStatus());

        $payment->setBuyerLastName('jenkins');
        $this->assertEquals('jenkins', $payment->getBuyerLastName());

        $payment->setBuyerFirstName('peter');
        $this->assertEquals('peter', $payment->getBuyerFirstName());

        $payment->setBuyerEmail('jenkins@github.com');
        $this->assertEquals('jenkins@github.com', $payment->getBuyerEmail());

        $payment->setRedirectUrl('https://nimbles.com/redirect/url');
        $this->assertEquals('https://nimbles.com/redirect/url', $payment->getRedirectUrl());

        $payment->setShippingCosts(500);
        $this->assertEquals(500, $payment->getShippingCosts());

        $this->assertFalse($payment->isSuccess());

        $this->assertTrue(is_string($payment->getToken()));
    }
}
