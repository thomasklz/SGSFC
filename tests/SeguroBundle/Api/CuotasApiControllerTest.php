<?php

use SeguroBundle\MyClass\uri;

class CuotasApiControllerTest extends PHPUnit_Framework_TestCase
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
    public function testCuotasPagar()
    {
        $response = $this->http->request('GET', 'meses/pagar/2016/14');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idmes', $data[0] );
    }
    public function testYearMeses()
    {
        $response = $this->http->request('GET', 'mes/year');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('year', $data[0] );
    }
    public function testPagoMes()
    {
        $response = $this->http->request('POST', 'meses/pagar',
                    ['query' => ['valor' => 2,'idusuario'=> 19,'idafiliado' => 14,'idmes'=> 2]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals(200, $response->getStatusCode());
    }
    public function testNewMes()
    {
       $meses=  array(1=>"Enero", 2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",7=>"Julio",8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre");
        $response = $this->http->request('POST', 'meses/',
                    ['query' => ['valor' => 1.5,'mes'=> $meses]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals(200, $response->getStatusCode());
    }
} 