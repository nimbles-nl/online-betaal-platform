<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Nimbles\OnlineBetaalPlatform\Model;

/**
 * Class Payment
 */
class Payment
{
    /** @var string */
    private $redirectUrl = null;

    /** @var string */
    private $returnUrl;

    /** @var string */
    private $uid = null;

    /** @var integer */
    private $amount;

    /** @var string */
    private $status = 'created';

    /** @var string */
    private $buyerFirstName;

    /** @var string */
    private $buyerLastName;

    /** @var string */
    private $buyerEmail;

    /** @var string */
    private $buyerAddress;

    /** @var string */
    private $buyerHouseNumber;

    /** @var string */
    private $buyerHouseNumberAdditional;

    /** @var string */
    private $buyerZipcode;

    /** @var string */
    private $buyerCity;

    /** @var string */
    private $buyerCountry = 'NLD';

    /** @var integer */
    private $shippingCosts = 0;

    /** @var string */
    private $token;

    /** @var array|Product[] */
    private $products = array();


    /**
     * @param string $returnUrl
     * @param int    $amount
     */
    public function __construct($returnUrl, $amount)
    {
        $this->returnUrl = $returnUrl;
        $this->amount    = $amount;

        $this->token = md5(time() . microtime() . uniqid() . mt_rand(1, 10000));
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
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
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getBuyerFirstName()
    {
        return $this->buyerFirstName;
    }

    /**
     * @param string $buyerFirstName
     */
    public function setBuyerFirstName($buyerFirstName)
    {
        $this->buyerFirstName = $buyerFirstName;
    }

    /**
     * @return string
     */
    public function getBuyerLastName()
    {
        return $this->buyerLastName;
    }

    /**
     * @param string $buyerLastName
     */
    public function setBuyerLastName($buyerLastName)
    {
        $this->buyerLastName = $buyerLastName;
    }

    /**
     * @return string
     */
    public function getBuyerEmail()
    {
        return $this->buyerEmail;
    }

    /**
     * @param string $buyerEmail
     */
    public function setBuyerEmail($buyerEmail)
    {
        $this->buyerEmail = $buyerEmail;
    }

    /**
     * @return int
     */
    public function getShippingCosts()
    {
        return $this->shippingCosts;
    }

    /**
     * @param int $shippingCosts
     */
    public function setShippingCosts($shippingCosts)
    {
        $this->shippingCosts = $shippingCosts;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->status === 'completed';
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    /**
     * @return array|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return string
     */
    public function getBuyerAddress()
    {
        return $this->buyerAddress;
    }

    /**
     * @param string $buyerAddress
     */
    public function setBuyerAddress($buyerAddress)
    {
        $this->buyerAddress = $buyerAddress;
    }

    /**
     * @return string
     */
    public function getBuyerHouseNumber()
    {
        return $this->buyerHouseNumber;
    }

    /**
     * @param string $buyerHouseNumber
     */
    public function setBuyerHouseNumber($buyerHouseNumber)
    {
        $this->buyerHouseNumber = $buyerHouseNumber;
    }

    /**
     * @return string
     */
    public function getBuyerHouseNumberAdditional()
    {
        return $this->buyerHouseNumberAdditional;
    }

    /**
     * @param string $buyerHouseNumberAdditional
     */
    public function setBuyerHouseNumberAdditional($buyerHouseNumberAdditional)
    {
        $this->buyerHouseNumberAdditional = $buyerHouseNumberAdditional;
    }

    /**
     * @return string
     */
    public function getBuyerZipcode()
    {
        return $this->buyerZipcode;
    }

    /**
     * @param string $buyerZipcode
     */
    public function setBuyerZipcode($buyerZipcode)
    {
        $this->buyerZipcode = $buyerZipcode;
    }

    /**
     * @return string
     */
    public function getBuyerCity()
    {
        return $this->buyerCity;
    }

    /**
     * @param string $buyerCity
     */
    public function setBuyerCity($buyerCity)
    {
        $this->buyerCity = $buyerCity;
    }

    /**
     * @return string
     */
    public function getBuyerCountry()
    {
        return $this->buyerCountry;
    }

    /**
     * @param string $buyerCountry
     */
    public function setBuyerCountry($buyerCountry)
    {
        $this->buyerCountry = $buyerCountry;
    }
}
