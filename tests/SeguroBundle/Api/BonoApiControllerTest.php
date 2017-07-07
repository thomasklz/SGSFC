<?php

use SeguroBundle\MyClass\uri;

class BonoApiControllerTest extends PHPUnit_Framework_TestCase
{
    private $http;
    public function setUp()
    {
        $this->http = new GuzzleHttp\Client(['base_uri' => uri::RUTA_API]);
    }

    public function tearDown()
    {
        $this->http = null;
    }
    public function testListBono()
    {
        $response = $this->http->request('GET', 'bono/listado');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idbono', $data[0] );
    }
    public function testOrden()
    {
        $response = $this->http->request('GET', 'orden/bono/15');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idorden', $data[0] );
    }
    public function testIngresos()
    {
        $response = $this->http->request('GET', 'ingresos',
                    ['query' => ['fecha' => '2017-05-13']]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('TotalDia', $data[0] );
    }

    public function testEgresos()
    {
        $response = $this->http->request('GET', 'egresos',
                    ['query' => ['fecha' => '2017-05-16']]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('TotalDia', $data[0] );
    }
}    