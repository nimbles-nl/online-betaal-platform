<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Tests\Nimbles\OnlineBetaalPlatform\Model;

use Nimbles\OnlineBetaalPlatform\Model\Payment;
use PHPUnit\Framework\TestCase;

/**
 * Class PaymentTest
 */
class PaymentTest extends TestCase
{
    public function testPayment()
    {
        $payment = new Payment('https://nimbles.com/return/url', 1050);

        $this->assertSame(1050, $payment->getAmount());
        $this->assertSame('https://nimbles.com/return/url', $payment->getReturnUrl());

        $payment->setUid('secret-uuid');
        $this->assertSame('secret-uuid', $payment->getUid());

        $payment->setStatus('created');
        $this->assertSame('created', $payment->getStatus());

        $payment->setBuyerLastName('jenkins');
        $this->assertSame('jenkins', $payment->getBuyerLastName());

        $payment->setBuyerFirstName('peter');
        $this->assertSame('peter', $payment->getBuyerFirstName());

        $payment->setBuyerEmail('jenkins@github.com');
        $this->assertSame('jenkins@github.com', $payment->getBuyerEmail());

        $payment->setRedirectUrl('https://nimbles.com/redirect/url');
        $this->assertSame('https://nimbles.com/redirect/url', $payment->getRedirectUrl());

        $payment->setShippingCosts(500);
        $this->assertSame(500, $payment->getShippingCosts());

        $this->assertFalse($payment->isSuccess());

        $this->assertTrue(is_string($payment->getToken()));
    }
}
