<?php

use SeguroBundle\MyClass\uri;

class MultasApiControllerTest extends PHPUnit_Framework_TestCase
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
    public function testDeleteMultasAfiliado()
    {
        $response = $this->http->request('delete', 'reunion/multas/88');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 

    }
    public function testNewMulta()
    {
        $response = $this->http->request('POST', 'asistencia',
                    ['query' => ['idafiliado' => 53,'idreunion'=> 21]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals(200, $response->getStatusCode());
    }
    public function testMultasReunion()
    {
        $response = $this->http->request('GET', 'reunion/inasistencia/21');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idafiliado', $data[0] );
    }
    public function testListMultasAfiliado()
    {
        $response = $this->http->request('GET', 'reunion/multas/53');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idafiliado', $data[0] );
    }
    public function testPagoMulta()
    {
        $response = $this->http->request('POST', 'multa/pagar',
                    ['query' => ['valor' => 2,'idusuario'=> 19,'idafiliado' => 53,'idmulta'=> 89]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals(200, $response->getStatusCode());
    }
}    