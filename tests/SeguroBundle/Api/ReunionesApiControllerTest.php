<?php

use SeguroBundle\MyClass\uri;

class ReunionesApiControllerTest extends PHPUnit_Framework_TestCase
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
    public function testNewReunion()
    {
        $response = $this->http->request('POST', 'reunion',
                    ['query' => ['tema' => 'Tema'.rand(1,100),'descripcion'=> 'descripcion'.rand(1,300),
                                 'fechareunion' => '2017-04-28 15:45:00','valorreunion'=> 2,
                                 'valormulta' => 5]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals(200, $response->getStatusCode()); 
    } 
    public function testOneReunionToday()
    {
        $response = $this->http->request('GET', 'reunion');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
    }
    public function testReunionList()
    {
        $response = $this->http->request('GET', 'reunion/listado');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('temas', $data[0] );
    }

     public function testReunionAdeudaSocio()
    {
        $response = $this->http->request('GET', 'reunion/adeuda/14');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('temas', $data[0] );
    } 
    public function testPagoReunion()
    {
        $response = $this->http->request('POST', 'reunion/pagar',
                    ['query' => ['valor' => rand(1,9),'idusuario'=> 19,
                                 'idafiliado' => 14,'idreunion'=> 33]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals(200, $response->getStatusCode()); 
    }        
       
}    