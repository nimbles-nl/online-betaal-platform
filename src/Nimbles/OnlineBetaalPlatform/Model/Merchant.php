<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Nimbles\OnlineBetaalPlatform\Model;

/**
 * Class Merchant
 */
class Merchant
{
    /** @var string */
    private $uid;

    /** @var string */
    private $name;

    /** @var \DateTime */
    private $created;

    /** @var \DateTime */
    private $updated;

    /** @var string */
    private $status;

    /** @var string */
    private $country;

    /**
     * @param string $uid
     * @param string $name
     * @param string $country
     */
    public function __construct($uid, $name, $country)
    {
        $this->uid     = $uid;
        $this->name    = $name;
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
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

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
