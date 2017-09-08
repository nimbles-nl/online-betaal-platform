<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Tests\Nimbles\OnlineBetaalPlatform\Adapter;

use GuzzleHttp\ClientInterface;
use Nimbles\OnlineBetaalPlatform\Model\Payment;
use Nimbles\OnlineBetaalPlatform\Adapter\OnlineBetaalPlatform;
use Nimbles\OnlineBetaalPlatform\Model\Product;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class OnlineBetaalPlatformTest
 */
class OnlineBetaalPlatformTest extends TestCase
{
    /** @var OnlineBetaalPlatform */
    private $onlineBetaalPlatform;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface */
    private $httpClient;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Payment */
    private $payment;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ResponseInterface */
    private $response;

    /** @var \PHPUnit_Framework_MockObject_MockObject|StreamInterface */
    private $stream;

    private $url = 'https://api.onlinebetaalplatform.nl';

    /** @var \PHPUnit_Framework_MockObject_MockObject|Product */
    private $product;

    public function setUp()
    {
        $this->httpClient   = $this->createHttpClientMock();
        $this->payment      = $this->createPaymentMock();
        $this->response     = $this->createResponseInterfaceMock();
        $this->stream       = $this->createStreamInterfaceMock();
        $this->product      = $this->createProductMock();

        $this->onlineBetaalPlatform = new OnlineBetaalPlatform($this->httpClient, 'secret-token', $this->url);
    }

    public function testCreateTransaction()
    {
        $this->payment->expects($this->once())
            ->method('getBuyerFirstName')
            ->willReturn('Klaas');

        $this->payment->expects($this->once())
            ->method('getMerchantUid')
            ->willReturn('merchant-uid');

        $this->payment->expects($this->once())
            ->method('getBuyerLastName')
            ->willReturn('Bruinsma');

        $this->payment->expects($this->once())
            ->method('getBuyerAddress')
            ->willReturn('Teststraat');

        $this->payment->expects($this->once())
            ->method('getBuyerHouseNumber')
            ->willReturn(66);

        $this->payment->expects($this->once())
            ->method('getBuyerHouseNumberAdditional')
            ->willReturn('zwart');

        $this->payment->expects($this->once())
            ->method('getBuyerZipcode')
            ->willReturn('1010AB');

        $this->payment->expects($this->once())
            ->method('getBuyerCity')
            ->willReturn('Haarlem');

        $this->payment->expects($this->once())
            ->method('getBuyerCountry')
            ->willReturn('NLD');

        $this->payment->expects($this->once())
            ->method('getBuyerEmail')
            ->willReturn('klaas@bruinsma.nl');

        $this->payment->expects($this->once())
            ->method('getAmount')
            ->willReturn(2500);

        $this->payment->expects($this->once())
            ->method('getShippingCosts')
            ->willReturn(100);

        $this->payment->expects($this->once())
            ->method('getReturnUrl')
            ->willReturn('https://www.foo.com/test');

        $this->payment->expects($this->once())
            ->method('getToken')
            ->willReturn('super-secret');

        $this->payment->expects($this->once())
            ->method('getProducts')
            ->willReturn([$this->product]);

        $this->product->expects($this->once())
            ->method('getName')
            ->willReturn('Online payment');

        $this->product->expects($this->once())
            ->method('getPrice')
            ->willReturn(2500);

        $this->product->expects($this->once())
            ->method('getQuantity')
            ->willReturn(1);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('POST', 'https://api.onlinebetaalplatform.nl/transactions', [
                'auth' => ['secret-token', null],
                'form_params' => [
                    'buyer_name_first'      => 'Klaas',
                    'buyer_name_last'       => 'Bruinsma',
                    'buyer_emailaddress'    => 'klaas@bruinsma.nl',
                    'merchant_uid'          => 'merchant-uid',
                    'products' => [
                        0 => [
                            'name' => 'Online payment',
                            'price' => 2500,
                            'quantity' => 1,
                        ],
                    ],
                    'shipping_costs' => 100,
                    'total_price'    => 2500,
                    'return_url'     => 'https://www.foo.com/test?token=super-secret',
                    'checkout'       => 'false',
                    'address'        => [
                        'address_line_1'       => 'Teststraat',
                        'housenumber'          => 66,
                        'housenumber_addition' => 'zwart',
                        'zipcode'              => '1010AB',
                        'city'                 => 'Haarlem',
                        'country'              => 'NLD',
                    ],
                ],
            ])
            ->willReturn($this->response);

        $this->response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/response.json'));

        $this->payment->expects($this->once())
            ->method('setRedirectUrl')
            ->with('http://www.redirect.to-ideal.nl');

        $this->payment->expects($this->once())
            ->method('setStatus')
            ->with('created');

        $this->payment->expects($this->once())
            ->method('setUid')
            ->with('foo-bar');

        $this->onlineBetaalPlatform->createTransaction($this->payment);
    }

    /**
     * @expectedException Nimbles\OnlineBetaalPlatform\Exception\CreatePaymentException
     */
    public function testCreatePaymentException()
    {
        $this->payment->expects($this->once())
            ->method('getBuyerFirstName')
            ->willReturn('Klaas');

        $this->payment->expects($this->once())
            ->method('getMerchantUid')
            ->willReturn('merchant-uid');

        $this->payment->expects($this->once())
            ->method('getBuyerLastName')
            ->willReturn('Bruinsma');

        $this->payment->expects($this->once())
            ->method('getBuyerEmail')
            ->willReturn('klaas@bruinsma.nl');

        $this->payment->expects($this->once())
            ->method('getBuyerAddress')
            ->willReturn('Teststraat');

        $this->payment->expects($this->once())
            ->method('getBuyerHouseNumber')
            ->willReturn(66);

        $this->payment->expects($this->once())
            ->method('getBuyerHouseNumberAdditional')
            ->willReturn('zwart');

        $this->payment->expects($this->once())
            ->method('getBuyerZipcode')
            ->willReturn('1010AB');

        $this->payment->expects($this->once())
            ->method('getBuyerCity')
            ->willReturn('Haarlem');

        $this->payment->expects($this->once())
            ->method('getBuyerCountry')
            ->willReturn('NLD');

        $this->payment->expects($this->once())
            ->method('getAmount')
            ->willReturn(2500);

        $this->payment->expects($this->once())
            ->method('getShippingCosts')
            ->willReturn(100);

        $this->payment->expects($this->once())
            ->method('getReturnUrl')
            ->willReturn('https://www.foo.com/test');

        $this->payment->expects($this->once())
            ->method('getToken')
            ->willReturn('super-secret');

        $this->payment->expects($this->once())
            ->method('getProducts')
            ->willReturn([$this->product]);

        $this->product->expects($this->once())
            ->method('getName')
            ->willReturn('Online payment');

        $this->product->expects($this->once())
            ->method('getPrice')
            ->willReturn(2500);

        $this->product->expects($this->once())
            ->method('getQuantity')
            ->willReturn(1);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('POST', 'https://api.onlinebetaalplatform.nl/transactions', [
                'auth' => ['secret-token', null],
                'form_params' => [
                    'buyer_name_first'      => 'Klaas',
                    'buyer_name_last'       => 'Bruinsma',
                    'buyer_emailaddress'    => 'klaas@bruinsma.nl',
                    'merchant_uid'          => 'merchant-uid',
                    'products' => [
                        0 => [
                            'name' => 'Online payment',
                            'price' => 2500,
                            'quantity' => 1,
                        ],
                    ],
                    'shipping_costs' => 100,
                    'total_price'    => 2500,
                    'return_url'     => 'https://www.foo.com/test?token=super-secret',
                    'checkout'       => 'false',
                    'address'        => [
                        'address_line_1'       => 'Teststraat',
                        'housenumber'          => 66,
                        'housenumber_addition' => 'zwart',
                        'zipcode'              => '1010AB',
                        'city'                 => 'Haarlem',
                        'country'              => 'NLD',
                    ],
                ],
            ])
            ->willReturn($this->response);

        $this->response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(502);

        $this->onlineBetaalPlatform->createTransaction($this->payment);
    }

    /**
     * @expectedException Nimbles\OnlineBetaalPlatform\Exception\CreatePaymentException
     */
    public function testCreatePaymentUnauthorizedPaymentException()
    {
        $this->payment->expects($this->once())
            ->method('getBuyerFirstName')
            ->willReturn('Klaas');

        $this->payment->expects($this->once())
            ->method('getBuyerLastName')
            ->willReturn('Bruinsma');

        $this->payment->expects($this->once())
            ->method('getMerchantUid')
            ->willReturn('merchant-uid');

        $this->payment->expects($this->once())
            ->method('getBuyerEmail')
            ->willReturn('klaas@bruinsma.nl');

        $this->payment->expects($this->once())
            ->method('getBuyerAddress')
            ->willReturn('Teststraat');

        $this->payment->expects($this->once())
            ->method('getBuyerHouseNumber')
            ->willReturn(66);

        $this->payment->expects($this->once())
            ->method('getBuyerHouseNumberAdditional')
            ->willReturn('zwart');

        $this->payment->expects($this->once())
            ->method('getBuyerZipcode')
            ->willReturn('1010AB');

        $this->payment->expects($this->once())
            ->method('getBuyerCity')
            ->willReturn('Haarlem');

        $this->payment->expects($this->once())
            ->method('getBuyerCountry')
            ->willReturn('NLD');

        $this->payment->expects($this->once())
            ->method('getAmount')
            ->willReturn(2500);

        $this->payment->expects($this->once())
            ->method('getShippingCosts')
            ->willReturn(100);

        $this->payment->expects($this->once())
            ->method('getReturnUrl')
            ->willReturn('https://www.foo.com/test');

        $this->payment->expects($this->once())
            ->method('getToken')
            ->willReturn('super-secret');

        $this->payment->expects($this->once())
            ->method('getProducts')
            ->willReturn([$this->product]);

        $this->product->expects($this->once())
            ->method('getName')
            ->willReturn('Online payment');

        $this->product->expects($this->once())
            ->method('getPrice')
            ->willReturn(2500);

        $this->product->expects($this->once())
            ->method('getQuantity')
            ->willReturn(1);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('POST', 'https://api.onlinebetaalplatform.nl/transactions', [
                'auth' => ['secret-token', null],
                'form_params' => [
                    'buyer_name_first'      => 'Klaas',
                    'buyer_name_last'       => 'Bruinsma',
                    'buyer_emailaddress'    => 'klaas@bruinsma.nl',
                    'merchant_uid'          => 'merchant-uid',
                    'products' => [
                        0 => [
                            'name' => 'Online payment',
                            'price' => 2500,
                            'quantity' => 1,
                        ],
                    ],
                    'shipping_costs' => 100,
                    'total_price'    => 2500,
                    'return_url'     => 'https://www.foo.com/test?token=super-secret',
                    'checkout'       => 'false',
                    'address'        => [
                        'address_line_1'       => 'Teststraat',
                        'housenumber'          => 66,
                        'housenumber_addition' => 'zwart',
                        'zipcode'              => '1010AB',
                        'city'                 => 'Haarlem',
                        'country'              => 'NLD',
                    ],
                ],
            ])
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(401);

        $this->onlineBetaalPlatform->createTransaction($this->payment);
    }

    /**
     * @expectedException Nimbles\OnlineBetaalPlatform\Exception\CreatePaymentException
     */
    public function testCreatePaymentBaseException()
    {
        $this->payment->expects($this->once())
            ->method('getBuyerFirstName')
            ->willReturn('Klaas');

        $this->payment->expects($this->once())
            ->method('getMerchantUid')
            ->willReturn('merchant-uid');

        $this->payment->expects($this->once())
            ->method('getBuyerLastName')
            ->willReturn('Bruinsma');

        $this->payment->expects($this->once())
            ->method('getBuyerEmail')
            ->willReturn('klaas@bruinsma.nl');

        $this->payment->expects($this->once())
            ->method('getBuyerAddress')
            ->willReturn('Teststraat');

        $this->payment->expects($this->once())
            ->method('getBuyerHouseNumber')
            ->willReturn(66);

        $this->payment->expects($this->once())
            ->method('getBuyerHouseNumberAdditional')
            ->willReturn('zwart');

        $this->payment->expects($this->once())
            ->method('getBuyerZipcode')
            ->willReturn('1010AB');

        $this->payment->expects($this->once())
            ->method('getBuyerCity')
            ->willReturn('Haarlem');

        $this->payment->expects($this->once())
            ->method('getBuyerCountry')
            ->willReturn('NLD');

        $this->payment->expects($this->once())
            ->method('getAmount')
            ->willReturn(2500);

        $this->payment->expects($this->once())
            ->method('getShippingCosts')
            ->willReturn(100);

        $this->payment->expects($this->once())
            ->method('getReturnUrl')
            ->willReturn('https://www.foo.com/test');

        $this->payment->expects($this->once())
            ->method('getToken')
            ->willReturn('super-secret');

        $this->payment->expects($this->once())
            ->method('getProducts')
            ->willReturn([$this->product]);

        $this->payment->expects($this->once())
            ->method('isCheckout')
            ->willReturn(false);

        $this->product->expects($this->once())
            ->method('getName')
            ->willReturn('Online payment');

        $this->product->expects($this->once())
            ->method('getPrice')
            ->willReturn(2500);

        $this->product->expects($this->once())
            ->method('getQuantity')
            ->willReturn(1);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('POST', 'https://api.onlinebetaalplatform.nl/transactions', [
                'auth' => ['secret-token', null],
                'form_params' => [
                    'buyer_name_first'      => 'Klaas',
                    'buyer_name_last'       => 'Bruinsma',
                    'buyer_emailaddress'    => 'klaas@bruinsma.nl',
                    'merchant_uid'          => 'merchant-uid',
                    'products' => [
                        0 => [
                            'name' => 'Online payment',
                            'price' => 2500,
                            'quantity' => 1,
                        ],
                    ],
                    'shipping_costs' => 100,
                    'total_price'    => 2500,
                    'return_url'     => 'https://www.foo.com/test?token=super-secret',
                    'checkout'       => 'false',
                    'address'        => [
                        'address_line_1'       => 'Teststraat',
                        'housenumber'          => 66,
                        'housenumber_addition' => 'zwart',
                        'zipcode'              => '1010AB',
                        'city'                 => 'Haarlem',
                        'country'              => 'NLD',
                    ],
                ],
            ])
            ->willReturn($this->response);

        $this->onlineBetaalPlatform->createTransaction($this->payment);
    }

    public function testGetTransaction()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'https://api.onlinebetaalplatform.nl/transactions/unique-id',
                ['auth' => ['secret-token', null]]
            )
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/response.json'));

        $this->onlineBetaalPlatform->getTransaction('unique-id');
    }

    /**
     * @expectedException Nimbles\OnlineBetaalPlatform\Exception\TransactionException
     */
    public function testGetException()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'https://api.onlinebetaalplatform.nl/transactions/unique-id',
                ['auth' => ['secret-token', null]]
            )
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(401);

        $this->onlineBetaalPlatform->getTransaction('unique-id');
    }

    /**
     * @expectedException Nimbles\OnlineBetaalPlatform\Exception\TransactionException
     */
    public function testGetExceptionForInvalidResponseCode()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'https://api.onlinebetaalplatform.nl/transactions/unique-id',
                ['auth' => ['secret-token', null]]
            )
            ->willThrowException(new \Exception());

        $this->onlineBetaalPlatform->getTransaction('unique-id');
    }

    public function testGetTransactions()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'https://api.onlinebetaalplatform.nl/transactions',
                ['auth' => ['secret-token', null],              'form_params' => [
                    'page'    => 1,
                    'perpage' => 20,
                ]]
            )
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/transactions.json'));

        $this->assertCount(2, $this->onlineBetaalPlatform->getTransactions(1, 20));
    }

    /**
     * @expectedException Nimbles\OnlineBetaalPlatform\Exception\TransactionException
     */
    public function testGetTransactionsException()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'https://api.onlinebetaalplatform.nl/transactions',
                ['auth' => ['secret-token', null],              'form_params' => [
                    'page'    => 1,
                    'perpage' => 10,
                ]]
            )
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(401);

        $this->onlineBetaalPlatform->getTransactions();
    }

    /**
     * @expectedException Nimbles\OnlineBetaalPlatform\Exception\TransactionException
     */
    public function testGetTransactionsExceptionGuzzle()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'https://api.onlinebetaalplatform.nl/transactions',
                ['auth' => ['secret-token', null],              'form_params' => [
                    'page'    => 1,
                    'perpage' => 10,
                ]]
            )
            ->willThrowException(new \Exception());

        $this->onlineBetaalPlatform->getTransactions();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    private function createHttpClientMock()
    {
        return $this->getMockBuilder(ClientInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Payment
     */
    private function createPaymentMock()
    {
        return $this->getMockBuilder(Payment::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ResponseInterface
     */
    private function createResponseInterfaceMock()
    {
        return $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|StreamInterface
     */
    private function createStreamInterfaceMock()
    {
        return $this->getMockBuilder(StreamInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createProductMock()
    {
        return $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
