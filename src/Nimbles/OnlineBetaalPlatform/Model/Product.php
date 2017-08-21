<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Nimbles\OnlineBetaalPlatform\Model;

/**
 * Class Product
 */
class Product
{
    /** @var string */
    private $name;

    /** @var integer */
    private $price;

    /** @var integer */
    private $quantity;

    /**
     * @param string $name
     * @param int    $price
     * @param int    $quantity
     */
    public function __construct($name, $price, $quantity = 1)
    {
        $this->name     = $name;
        $this->price    = $price;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
