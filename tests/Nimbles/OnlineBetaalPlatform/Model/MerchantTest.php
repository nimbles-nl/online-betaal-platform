<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Tests\Nimbles\OnlineBetaalPlatform\Model;

use Nimbles\OnlineBetaalPlatform\Model\Merchant;
use PHPUnit\Framework\TestCase;

/**
 * Class MerchantTest
 */
class MerchantTest extends TestCase
{
    public function testMerchant()
    {
        $date = new \DateTime('now');

        $merchant = new Merchant('secret-uuid', 'Willem Holleeder', 'nld');
        $merchant->setStatus('verified');
        $merchant->setUpdated($date);
        $merchant->setCreated($date);

        $this->assertSame('nld', $merchant->getCountry());
        $this->assertSame('Willem Holleeder', $merchant->getName());
        $this->assertSame('secret-uuid', $merchant->getUid());
        $this->assertEquals($date, $merchant->getCreated());
        $this->assertSame($date, $merchant->getUpdated());
        $this->assertSame('verified', $merchant->getStatus());
    }
}
