<?php

use SeguroBundle\MyClass\uri;

class UserApiControllerTest extends PHPUnit_Framework_TestCase
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

    public function testGetAllUser()
    {
        $response = $this->http->request('GET', 'usuarios');
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $data = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('Usuarios', $data);
    }

    public function testPost()
    {
       $user=rand(1,300);
       $response = $this->http->request('POST', 'usuario/', 
            ['query' => ['usuario' => 'Prueba'.$user, 'password' => 123, 'idtipousuario' => 3, 'idafiliado'=> 125 ]]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetUser()
    {
        $response = $this->http->request('GET', 'usuario/19');
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('usuario', ['usuario' => $data] );
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('tipo', ['tipo' => $data] );
    }
    
    public function testLogin()
    {
        $response = $this->http->request('POST', 'logearse',
                    ['query' => ['user' => 'ThomasL', 'password' => 123 ]]);
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testUpdateUser()
    {
        $user=rand(1,300);
        $response = $this->http->request('PUT', 'usuario/25', 
            ['query' => ['usuario' => 'Prueba'.$user, 'idTipouser' => 3, 'password' => 123, 'estado'=> 1 ]]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testOneSocioUser()
    {
        $response = $this->http->request('GET', 'usuario/socio/25');
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testUpdatePhotoUser()
    {
        $response = $this->http->request('PUT', 'usuario/foto/25', 
            ['query' => ['foto' => 'foto1.jpeg']]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateUsernameUser()
    {
        $response = $this->http->request('PUT', 'usuario/username/25', 
            ['query' => ['username' => 'Prueba2']]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdatePasswordUser()
    {
        $response = $this->http->request('PUT', 'usuario/password/25', 
            ['query' => ['password' => 123,'passworanterior' => '123']]);
        $this->assertEquals(200, $response->getStatusCode());
    }

   
}