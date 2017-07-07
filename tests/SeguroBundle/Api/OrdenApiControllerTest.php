<?php

use SeguroBundle\MyClass\uri;

class OrdenApiControllerTest extends PHPUnit_Framework_TestCase
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
    public function testOrdenPendiente()
    {
        $response = $this->http->request('GET', 'fallecidos/orden');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idafiliado', $data[0] );
    }
    public function testNewOrden()
    {
        $response = $this->http->request('POST', 'orden',
                    ['query' => ['idbono' => 3,'idafiliado'=> 132,
                                 'idusuario' => 19]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idorden', $data[0] ); 
    }
    public function testOrdenPendienteRegistrar()
    {
        $response = $this->http->request('GET', 'orden/pendiente');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idorden', $data[0] );
    }
    public function testResgiterOrden()
    {
        $response = $this->http->request('POST', 'orden/registrar',
                    ['query' => ['idorden' => 13,'valor'=> 200,
                                 'idusuario' => 19]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals(200, $response->getStatusCode()); 
    }
    public function testLastOrdenRegistrar()
    {
        $response = $this->http->request('GET', 'ordenes/registradas');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idorden', $data[0] );
    }
    public function testBonoAndService()
    {
        $response = $this->http->request('GET', 'orden/13');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idorden', $data[0] );
    }
         
}    