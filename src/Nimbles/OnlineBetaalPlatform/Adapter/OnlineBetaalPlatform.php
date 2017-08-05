<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Nimbles\OnlineBetaalPlatform\Adapter;

use GuzzleHttp\ClientInterface;
use Nimbles\OnlineBetaalPlatform\Exception\CreatePaymentException;
use Nimbles\OnlineBetaalPlatform\Exception\TransactionException;
use Nimbles\OnlineBetaalPlatform\Exception\UnauthorizedPaymentException;
use Nimbles\OnlineBetaalPlatform\Model\Payment;

/**
 * Class OnlineBetaalPlatform
 */
class OnlineBetaalPlatform
{
    /** @var ClientInterface */
    private $httpClient;

    /** @var string */
    private $apiKey;

    /** @var string */
    private $uri;

    /** @var string */
    private $merchantUid;

    /**
     * @param ClientInterface $httpClient
     * @param string          $apiKey
     * @param string          $uri
     * @param string          $merchantUid
     */
    public function __construct(ClientInterface $httpClient, $apiKey, $uri, $merchantUid)
    {
        $this->httpClient  = $httpClient;
        $this->apiKey      = $apiKey;
        $this->uri         = $uri;
        $this->merchantUid = $merchantUid;
    }

    /**
     * @param Payment $payment
     *
     * @return Payment
     *
     * @throws CreatePaymentException
     * @throws UnauthorizedPaymentException
     */
    public function createTransaction(Payment $payment)
    {
        try {
            $url = sprintf('%s/transactions', $this->uri);

            $response = $this->httpClient->request('POST', $url, [
                'auth' => [$this->apiKey, null], 'form_params' => [
                    'buyer_name_first'      => $payment->getBuyerFirstName(),
                    'buyer_name_last'       => $payment->getBuyerLastName(),
                    'buyer_emailaddress'    => $payment->getBuyerEmail(),
                    'merchant_uid'          => $this->merchantUid,
                    'products' => [
                        0 => [
                            'name' => 'Online payment',
                            'price' => $payment->getAmount(),
                            'quantity' => 1,
                        ],
                    ],
                    'shipping_costs' => $payment->getShippingCosts(),
                    'total_price'    => $payment->getAmount(),
                    'return_url'     => $payment->getReturnUrl() . '?token=' . $payment->getToken(),
                ],
            ]);

            if ($response->getStatusCode() === 401) {
                throw new CreatePaymentException('Invalid credentials given');
            }

            if ($response->getStatusCode() !== 200) {
                throw new CreatePaymentException('The api does not return a 200 status code');
            }

            $data = json_decode($response->getBody()->getContents(), true);

            $payment->setRedirectUrl($data['redirect_url']);
            $payment->setStatus($data['status']);
            $payment->setUid($data['uid']);

            return $payment;

        } catch (\Exception $exception) {
            throw new CreatePaymentException($exception->getMessage());
        }
    }

    /**
     * @param string $uuid
     *
     * @return Payment
     *
     * @throws TransactionException
     */
    public function getTransaction($uuid)
    {
        try {
            $url = sprintf('%s/transactions/%s', $this->uri, $uuid);

            $response = $this->httpClient->request('GET', $url, [
                'auth' => [$this->apiKey, null]
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new TransactionException('Invalid response');
            }

            $data = json_decode($response->getBody()->getContents(), true);

            $payment = new Payment($data['return_url'], $data['amount']);
            $payment->setStatus($data['status']);
            $payment->setUid($data['uid']);

            return $payment;

        } catch (\Exception $exception) {
            throw new TransactionException($exception->getMessage());
        }
    }
}
