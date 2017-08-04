<?php

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

    /** @var integer */
    private $shippingCosts = 0;

    /** @var string */
    private $token;

    /** @var Product[]|array */
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
}