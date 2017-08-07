<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Tests\Nimbles\OnlineBetaalPlatform\Model;

use Nimbles\OnlineBetaalPlatform\Model\Transaction;
use PHPUnit\Framework\TestCase;

/**
 * Class TransactionTest
 */
class TransactionTest extends TestCase
{
    public function testTransaction()
    {
        $transaction = new Transaction('secret-uuid', 1050, 'created');
        $this->assertSame(1050, $transaction->getAmount());
        $this->assertSame('secret-uuid', $transaction->getUuid());
        $this->assertSame('created', $transaction->getStatus());

        $transaction->setCreated(new \DateTime('20-01-2017'));

        $this->assertEquals(new \DateTime('20-01-2017'), $transaction->getCreated());
    }
}
