<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Nimbles\OnlineBetaalPlatform\Model;

/**
 * Class Transaction
 */
class Transaction
{
    /** @var string */
    private $uuid;

    /** @var integer */
    private $amount;

    /** @var string */
    private $status;

    /** @var \DateTime */
    private $created;

    /**
     * @param $uuid
     * @param $amount
     * @param $status
     */
    public function __construct($uuid, $amount, $status)
    {
        $this->uuid   = $uuid;
        $this->amount = $amount;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
}
