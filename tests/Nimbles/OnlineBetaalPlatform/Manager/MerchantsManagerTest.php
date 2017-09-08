<?php
/*
* (c) Nimbles b.v. <wessel@nimbles.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Tests\Nimbles\OnlineBetaalPlatform\Manager;

use GuzzleHttp\ClientInterface;
use Nimbles\OnlineBetaalPlatform\Manager\MerchantsManager;
use Nimbles\OnlineBetaalPlatform\Model\Merchant;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class MerchantsManagerTest
 */
class MerchantsManagerTest extends TestCase
{
    /** @var MerchantsManager */
    private $merchantsManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface */
    private $httpClient;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ResponseInterface */
    private $response;

    /** @var \PHPUnit_Framework_MockObject_MockObject|StreamInterface */
    private $stream;

    private $apiUrl = 'https://dev.online-betaal-platform.nl/v1';

    public function setUp()
    {
        $this->httpClient = $this->createHttpClientMock();
        $this->response   = $this->createResponseInterfaceMock();
        $this->stream     = $this->createStreamInterfaceMock();

        $this->merchantsManager = new MerchantsManager($this->httpClient, 'secret-api-key', $this->apiUrl);
    }

    public function testCreateMerchant()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'response.json'));

        $merchant = $this->merchantsManager->createMerchant('Klaas', 'Bruinsma', 'klaas@tester.nl', 31612345678);
        
        $this->assertInstanceOf(Merchant::class, $merchant);
    }


    /**
     * @expectedException \Exception
     */
    public function testCreateMerchantExceptionNoValidReponse()
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(422);

        $this->response->expects($this->never())
            ->method('getBody');

        $merchant = $this->merchantsManager->createMerchant('Klaas', 'Bruinsma', 'klaas@tester.nl', 31612345678);

        $this->assertInstanceOf(Merchant::class, $merchant);
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
