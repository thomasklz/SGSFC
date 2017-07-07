<?php

namespace SeguroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use SeguroBundle\MyClass\uri;
use SeguroBundle\MyClass\functiones;

class MultasController extends Controller
{
/**
*@Route("reunion/multas/{idmulta}", name="multas_delete")
*/
public function multasdeleteAction(Request $request,$idmulta)
  {
    $cliente = new Client([
                       'base_uri' => uri::RUTA_API,
                       'exceptions' => false,
                      ]);
    $responseMultas = $cliente->request('delete', 'reunion/multas/'.$idmulta);
    $bodyMultas = functiones::DecoJson($responseMultas);
      if ($responseMultas->getStatusCode()==200)
      {
          $message = functiones::DecoMessage($bodyMultas);
          $this->addFlash('correcto', $message);
          return $this->redirectToRoute('asistencia_reunion'); 
      } 
      else
      {
          $message = functiones::DecoMessage($bodyMultas);
          $this->addFlash('incorrecto', $message);
          return $this->redirectToRoute('asistencia_reunion'); 
    } 
  }
  /**
*@Route("registrar/asistencia", name="asistencia_registrar")
*@Method({"Post"})
*/
public function asistenciaAction(Request $request){
  $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
  $idreunion= $request->get('idreunion');
  $idafiliado = $request->get('idafiliado');
  $responseAsistencia = $cliente->request('POST', 'asistencia',
                          ['query' => ['idreunion' => $idreunion, 
                                   'idafiliado' => $idafiliado]]);
  $bodyAsistencia = functiones::DecoJson($responseAsistencia);
    if ($responseAsistencia->getStatusCode()==201)
    {
        
        return new Response(json_encode($bodyAsistencia), 200, array('Content-Type' => 'application/json'));
    } 
    else
    {
        return new Response(json_encode($bodyAsistencia), 404, array('Content-Type' => 'application/json'));
  } 
}
/**
*@Route("lista/multas", name="multas_lista")
*@Method({"Get"})
*/
public function multasAction(Request $request){
  $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
  $idafiliado = $request->get('idafiliado');
  $responseMultas = $cliente->request('GET', 'reunion/multas/'.$idafiliado);
  $bodyMultas = functiones::DecoJson($responseMultas);
    if ($responseMultas->getStatusCode()==200)
    {
        return new Response(json_encode($bodyMultas), 200, array('Content-Type' => 'application/json'));
    } 
    else
    {
        return new Response(json_encode($bodyMultas), 404, array('Content-Type' => 'application/json'));
  } 
}
/**
  * @Route("multa/pago", name="pago_multa")
*/
public function pagomultaAction(Request $request)
{
  if ($request->isXmlHttpRequest())
    {
      $valor=$request->get('valor');
      $idusuario=$request->get("idusuario");
      $idafiliado=$request->get('idafiliado');
      $idmulta = $request->get('idmulta');
      $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
      $response = $cliente->request('POST', 'multa/pagar',
                            ['query' => [
                                        'valor' => $valor,
                                        'idusuario' => $idusuario,
                                        'idafiliado' => $idafiliado,
                                        'idmulta' => $idmulta]
                            ]);
      $bodyMultas = functiones::DecoJson($response);
      if ($response->getStatusCode()==200)
        { 
          return new Response(json_encode($bodyMultas),200,array('Content-Type' => 'application/json'));
        } 
      else
        {
          return new Response(json_encode($bodyMultas),400,array('Content-Type' => 'application/json'));
        }
    }
  }
}