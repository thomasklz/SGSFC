<?php

use SeguroBundle\MyClass\uri;

class SocioApiControllerTest extends PHPUnit_Framework_TestCase
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

    public function testGetSocios()
    {
        $response = $this->http->request('GET', 'socios');
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $data = json_decode($response->getBody(true), true);
        $this->assertEquals($data[0]['idafiliado'], $data[0]['idafiliado']);
    }

    public function testGetOneSocio()
    {
        $response = $this->http->request('GET', 'socio/14');
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $data = json_decode($response->getBody(true), true);
        $this->assertEquals('14', $data[0]['idafiliado']);
    }

    public function testSearchSocioCedula()
    {
        $response = $this->http->request('POST', 'socio/search/',
                    ['query' => ['cedula' => 31232222211]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType); 
    }

    public function testSearchSocioAfiliadoCedula()
    {
        $response = $this->http->request('POST', 'socio/afiliado/search/',
                    ['query' => ['cedula' => 31232222211]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType); 
    }

    public function testNewSocio()
    {
       $socio=rand(10,99);
       $response = $this->http->request('POST', 'socio/', 
            ['query' => ['nombre' => 'Socio'.$socio, 'apellido' => 'LL'.$socio, 'cedula' => '13199999'.$socio, 'fechanacimiento'=> '1999/03/05', 'sexo' => 'M', 'parentesco' => 'Hijo', 'tipoafiliacion' => 'Socio' ]]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateSocio()
    {
       $socio=rand(10,99);
       $response = $this->http->request('PUT', 'socio/48', 
            ['query' => ['nombre' => 'Socio'.$socio, 'apellido' => 'LL'.$socio, 'cedula' => '13199999'.$socio, 'fechanacimiento'=> '1990/03/05', 'sexo' => 'M', 'parentesco' => 'Hijo']]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idafiliado', ['idafiliado' => $data]);
    }

     public function testPagosSocio()
    {
        $response = $this->http->request('GET', 'usuario/pagos/14');
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('valor', ['valor' => $data]);
  
    }
}    