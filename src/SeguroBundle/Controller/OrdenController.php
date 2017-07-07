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

class OrdenController extends Controller
{
  /**
  *@Route("nueva/orden", name="orden_nueva")
  *@Method({"Post"})
  */
  public function nuevaAction(Request $request){
    $idbono=$request->get('idbono');
    $idafiliado=$request->get('idafiliado');
    $idusuario=$request->get('idusuario');
    $cliente = new Client([ 'base_uri' => uri::RUTA_API,'exceptions' => false]);
    $response = $cliente->request('POST', 'orden',
                            ['query' => [
                                        'idbono' => $idbono,
                                        'idafiliado' => $idafiliado,
                                        'idusuario' => $idusuario
                                        ]
                            ]);
    $orden =  functiones::DecoJson($response);
    if ($response->getStatusCode()==200)
      {
       return new Response(json_encode($orden),200,array('Content-Type' => 'application/json'));
      }
    else
      {
        return new Response(json_encode($orden),400,array('Content-Type' => 'application/json'));
      }
  }
  /**
  *@Route("orden/servicios", name="orden_servicios")
  *@Method({"Post"})
  */
  public function ordenServiciosAction(Request $request){
    $idorden=$request->get('idorden');
    $idservicio = json_decode(stripslashes($_POST['idservicio']));
    $cliente = new Client([ 'base_uri' => uri::RUTA_API,'exceptions' => false]);
    $response = $cliente->request('POST', 'orden/servicios',
                            ['query' => [
                                        'idorden' => $idorden,
                                        'idservicio' => $idservicio
                                        ]
                            ]);
    $ordenServicios =  functiones::DecoJson($response);
    if ($response->getStatusCode()==200)
      {
       return new Response(json_encode($ordenServicios),200,array('Content-Type' => 'application/json'));
      }
    else
      {
        return new Response(json_encode($ordenServicios),400,array('Content-Type' => 'application/json'));
      }
  }
  /**
  *@Route("generar/orden/registrar", name="orden_registrar")
  */
  public function registrarAction(Request $request){
    $rol= $this->get('session')->get('idtipousuario');
    if (($rol['tipo'] != 'ROLE_ADMIN')) {
            return $this->render('SeguroBundle:Default:index.html.twig');
    }else{  
      $cliente = new Client([ 'base_uri' => uri::RUTA_API,'exceptions' => false]);
      $response = $cliente->request('GET', 'orden/pendiente');
      $ordenPendiente =  functiones::DecoJson($response);
      $responseRegistradas = $cliente->request('GET', 'ordenes/registradas');
      $ordenRegistradas =  functiones::DecoJson($responseRegistradas);
      if ($response->getStatusCode()==200)
        {    
         if ($responseRegistradas->getStatusCode()==200)
              {
                return $this->render("SeguroBundle:Servicios:registrar.html.twig", 
                                array('listaOrdenes'=>$ordenPendiente, 'listaUltimas'=> $ordenRegistradas));
              }
              else
              {
                $message = functiones::DecoMessage($ordenRegistradas);
                $this->addFlash('incorrecto1', $message);
                return $this->render("SeguroBundle:Servicios:registrar.html.twig",array('listaOrdenes'=>$ordenPendiente));
              }
        }
      else
        {
          if ($responseRegistradas->getStatusCode()==200)
              {
               $message = functiones::DecoMessage($ordenPendiente);
               $this->addFlash('incorrecto', $message);
               return $this->render("SeguroBundle:Servicios:registrar.html.twig",array('listaUltimas'=>$ordenRegistradas));
              }
              else
              {
                $message = functiones::DecoMessage($ordenPendiente);
                $this->addFlash('incorrecto', $message);
                $message = functiones::DecoMessage($ordenRegistradas);
                $this->addFlash('incorrecto1', $message);
                return $this->render("SeguroBundle:Servicios:registrar.html.twig");
              }
        }
      }  
  }
  /**
  *@Route("orden/resgitrar", name="registrar_orden")
  *@Method({"Post"})
  */
  public function ordenregistrarAction(Request $request){
    $idorden=$request->get('idorden');
    $valorServicio=$request->get('valorServicio');
    $valorOrden=$request->get('valorOrden');
    $idusuario = $this->get('session')->get('idusuario');
    $valor= $valorServicio + $valorOrden;
    $cliente = new Client([ 'base_uri' => uri::RUTA_API,'exceptions' => false]);
    $response = $cliente->request('POST', 'orden/registrar',
                            ['query' => [
                                        'idorden' => $idorden,
                                        'idusuario' => $idusuario,
                                        'valor' => $valor
                                        ]
                            ]);
    $ordenRegistrar =  functiones::DecoJson($response);
    if ($response->getStatusCode()==200)
      {
       
       $this->addFlash('correcto1', "Orden registrada correctamente");
       return $this->redirectToRoute('orden_registrar');
      }
    else
      {
        $message = functiones::DecoMessage($ordenRegistrar);
        $this->addFlash('incorrecto', $message);
        return $this->redirectToRoute('orden_registrar');
      }
  }
  /**
  *@Route("generar/orden/ver", name="orden_ver")
  *@Method({"Get"})
  */
  public function ordenVerAction(Request $request){
    $idorden=$request->get('idorden');
    $cliente = new Client([ 'base_uri' => uri::RUTA_API,'exceptions' => false]);
    $response = $cliente->request('GET', 'orden/'.$idorden);
    $orden =  functiones::DecoJson($response);
    if ($response->getStatusCode()==200)
      {
       return new Response(json_encode($orden),200,array('Content-Type' => 'application/json'));
      }
    else
      {
        return new Response(json_encode($orden),400,array('Content-Type' => 'application/json'));
      }
  }

}