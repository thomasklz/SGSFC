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

    public function testGet()
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
        $response = $this->http->request('POST', 'usuario/', 
            ['json' => ['usuario' => 'JuanS', 'password' => 1234, 'idtipousuario' => 1 ]]);
       // $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode());
    }
}