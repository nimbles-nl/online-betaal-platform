<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Tests\Nimbles\OnlineBetaalPlatform\Adapter;

use GuzzleHttp\ClientInterface;
use Nimbles\OnlineBetaalPlatform\Exception\UnauthorizedPaymentException;
use Nimbles\OnlineBetaalPlatform\Model\Payment;
use Nimbles\OnlineBetaalPlatform\Adapter\OnlineBetaalPlatform;
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

    public function setUp()
    {
        $this->httpClient = $this->createHttpClientMock();
        $this->payment = $this->createPaymentMock();
        $this->response = $this->createResponseInterfaceMock();
        $this->stream   = $this->createStreamInterfaceMock();

        $this->onlineBetaalPlatform = new OnlineBetaalPlatform($this->httpClient, 'secret-token', $this->url, 'secret-id');
    }

    public function testCreateTransaction()
    {
        $this->payment->expects($this->once())
            ->method('getBuyerFirstName')
            ->willReturn('Klaas');

        $this->payment->expects($this->once())
            ->method('getBuyerLastName')
            ->willReturn('Bruinsma');

        $this->payment->expects($this->once())
            ->method('getBuyerEmail')
            ->willReturn('klaas@bruinsma.nl');

        $this->payment->expects($this->exactly(2))
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

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('POST', 'https://api.onlinebetaalplatform.nl/transactions', [
                'auth' => ['secret-token', null],
                'form_params' => [
                    'buyer_name_first'      => 'Klaas',
                    'buyer_name_last'       => 'Bruinsma',
                    'buyer_emailaddress'    => 'klaas@bruinsma.nl',
                    'merchant_uid'          => 'secret-id',
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
            ->method('getBuyerLastName')
            ->willReturn('Bruinsma');

        $this->payment->expects($this->once())
            ->method('getBuyerEmail')
            ->willReturn('klaas@bruinsma.nl');

        $this->payment->expects($this->exactly(2))
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

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('POST', 'https://api.onlinebetaalplatform.nl/transactions', [
                'auth' => ['secret-token', null],
                'form_params' => [
                    'buyer_name_first'      => 'Klaas',
                    'buyer_name_last'       => 'Bruinsma',
                    'buyer_emailaddress'    => 'klaas@bruinsma.nl',
                    'merchant_uid'          => 'secret-id',
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
                ],
            ])
            ->willReturn($this->response);

        $this->response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(502);

        $this->onlineBetaalPlatform->createTransaction($this->payment);
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
}
