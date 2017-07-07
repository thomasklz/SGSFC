<?php

use SeguroBundle\MyClass\uri;

class ServiciosApiControllerTest extends PHPUnit_Framework_TestCase
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

    public function testNewService()
    {
        $response = $this->http->request('POST', 'servicio',
                    ['query' => ['servicio' => 'servicio'.rand(1,9),'valor'=> rand(100,300)]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType); 
        $this->assertEquals(200, $response->getStatusCode()); 
    }
    public function testListService()
    {
        $response = $this->http->request('GET', 'servicio/listado');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
    }
    public function testTiempoService()
    {
        $response = $this->http->request('GET', 'tiempo/afiliacion/14');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
    }
    public function testServiceOnOrden()
    {
        $arrayService= array('1','3');
        $response = $this->http->request('POST', 'orden/servicios',
                    ['query' => ['idorden' => 12,'idservicio'=> $arrayService]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType); 
    }
     public function testServiceOfOrden()
    {
        $response = $this->http->request('GET', 'orden/servicios/14');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
    }
}    