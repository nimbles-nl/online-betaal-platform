<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Tests\Nimbles\OnlineBetaalPlatform\Model;

use Nimbles\OnlineBetaalPlatform\Model\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductTest
 */
class ProductTest extends TestCase
{
    public function testProduct()
    {
        $product = new Product('Test', 999, 2);

        $this->assertSame($product->getName(), 'Test');
        $this->assertSame($product->getPrice(), 999);
        $this->assertSame($product->getQuantity(), 2);
    }
}
