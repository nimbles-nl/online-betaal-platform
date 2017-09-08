<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Nimbles\OnlineBetaalPlatform\Manager;

use GuzzleHttp\ClientInterface;
use Nimbles\OnlineBetaalPlatform\Model\Merchant;

/**
 * Class MerchantsManager
 */
class MerchantsManager
{
    /** @var ClientInterface */
    private $httpClient;

    /** @var string */
    private $apiKey;

    /** @var string */
    private $uri;

    /**
     * @param ClientInterface $httpClient
     * @param string          $apiKey
     * @param string          $uri
     */
    public function __construct(ClientInterface $httpClient, $apiKey, $uri)
    {
        $this->httpClient = $httpClient;
        $this->apiKey     = $apiKey;
        $this->uri        = $uri;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $phone
     * @param string $country
     *
     * @return Merchant
     *
     * @throws \Exception
     */
    public function createMerchant($firstName, $lastName, $email, $phone, $country = 'nld')
    {
        try {
            $uri = sprintf('%s/merchants', $this->uri);

            $response = $this->httpClient->request('POST', $uri, [
                'auth' => [$this->apiKey, null], 'form_params' => [
                    'country'       => $country,
                    'name_first'    => $firstName,
                    'name_last'     => $lastName,
                    'emailaddress'  => $email,
                    'phone'         => $phone,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Invalid response');
            }

            $data = json_decode($response->getBody()->getContents(), true);

            $merchant = new Merchant($data['uid'], $data['name'], $data['country']);

            $merchant->setStatus($data['compliance']['status']);

            $merchant->setCreated((new \DateTime())->setTimestamp($data['created']));
            $merchant->setUpdated((new \DateTime())->setTimestamp($data['updated']));

            return $merchant;

        } catch (\Exception $exception) {
            throw new \Exception('Unable to create merchant');
        }
    }
}
