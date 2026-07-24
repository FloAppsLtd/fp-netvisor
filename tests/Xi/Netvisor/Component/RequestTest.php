<?php

namespace Xi\Netvisor\Component;

use GuzzleHttp\Psr7\Response;
use Xi\Netvisor\Component\Request;
use Xi\Netvisor\Config;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Xi\Netvisor\Exception\NetvisorException;
use PHPUnit\Framework\Attributes\Test;

class RequestTest extends TestCase
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $config = new Config(
            true,
            'http://integration.netvisor.fi',
            'sender',
            'customerId',
            'partnerId',
            'language',
            'organizationId',
            'userKey',
            'partnerKey'
        );

        $this->request = new Request($this->client, $config);
    }

    #[Test]
    public function createsRequest()
    {
        $this->client->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'http://integration.netvisor.fi/accounting.nv',
                $this->anything()
            )
            ->willReturn(
                new Response('200', array(), 'hello')
            );

        $this->request->post(
            '<?xml>',
            'accounting'
        );
    }

    #[Test]
    public function throwsExceptionIfResponseStatusIsFailed()
    {
        $xmlResponse = <<<LUS
<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<Root>
    <ResponseStatus>
        <Status>FAILED</Status>
        <Status>AUTHENTICATION_FAILED :: Integraatiokumppania ei löydy, katso dokumentaatio</Status>
        <TimeStamp>7.4.2009 13:46:07</TimeStamp>
    </ResponseStatus>
</Root>
LUS;

        $this->client->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'http://integration.netvisor.fi/accounting.nv',
                $this->anything()
            )
            ->willReturn(
                new Response('200', array(), $xmlResponse)
            );

        $this->expectException(NetvisorException::class);
        $this->expectExceptionMessage('AUTHENTICATION_FAILED :: Integraatiokumppania ei löydy, katso dokumentaatio');

        $this->request->post(
            '<?xml>',
            'accounting'
        );
    }

    #[Test]
    public function returnsResponseBodyIfResponseStatusIsOK()
    {
        $xmlResponse = <<<LUS
<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<Root>
    <ResponseStatus>
        <Status>OK</Status>
        <TimeStamp>7.4.2009 13:37:00</TimeStamp>
    </ResponseStatus>
</Root>
LUS;

        $this->client->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'http://integration.netvisor.fi/accounting.nv',
                $this->anything()
            )
            ->willReturn(
                new Response('200', array(), $xmlResponse)
            );

        $response = $this->request->post(
            '<?xml>',
            'accounting'
        );

        $this->assertEquals($xmlResponse, $response);
    }
}
