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
    public function testListAfiliadosOfSocio()
    {
        $response = $this->http->request('GET', 'socio/14/afiliados');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idafiliado', $data[0] );
    }
    public function testNewAfiliado()
    {
        $Beneficiario=rand(10,99);
        $response = $this->http->request('POST', 'beneficiario/', 
            ['query' => ['nombre' => 'Beneficiario'.$Beneficiario, 'apellido' => 'Loor'.$Beneficiario, 'cedula' => '13199999'.$Beneficiario, 'fechanacimiento'=> '1999/03/05', 'sexo' => 'M', 'parentesco' => 'Hijo', 'tipoafiliacion' => 'Afiliado', 'idafiliacionedependiente' => '14' ]]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals(200, $response->getStatusCode());
    }
    public function testListFallecidos()
    {
        $response = $this->http->request('GET', 'socios/afiliados/fallecidos');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('nombre', $data[0] );
    }
    public function testUpdateFallecido()
    {
        $Beneficiario=rand(10,99);
        $response = $this->http->request('PUT', 'socios/afiliados/48/fallecidos', 
            ['query' => ['estado' => 1,  'fechafallecido'=> '2017/03/05']]);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals(200, $response->getStatusCode());
    }
    public function testGetAfiliado()
    {
        $response = $this->http->request('GET', 'beneficiario/136');
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals(200, $response->getStatusCode()); 
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('idafiliado', $data[0] );
    }
}    