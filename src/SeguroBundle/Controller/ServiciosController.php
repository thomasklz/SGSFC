<?php

namespace SeguroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use SeguroBundle\Form\ServicioType;
use SeguroBundle\MyClass\uri;
use SeguroBundle\MyClass\functiones;
use SeguroBundle\Controller\BeneficiarioController;
use SeguroBundle\Entity\Servicio;


class ServiciosController extends Controller
{
/**
*@Route("nuevo/servicio", name="servicio_nuevo")
*/
public function nuevoAction(Request $request){
  $rol= $this->get('session')->get('idtipousuario');
  if ($rol['tipo'] != 'ROLE_ADMIN') {
          return $this->render('SeguroBundle:Default:index.html.twig');
  }else{
  	$servicio = new Servicio();
    $form = $this->createForm(ServicioType::class,$servicio,array(
                  'action' => $this->generateUrl('servicio_nuevo'),
                  'method'=> 'Post'
                  ));     
    if ($request->getMethod()=="POST")
      { 
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) 
          {
            $servicio=$form->get('servicio')->getData();
            $valor= $form->get('valor')->getData();
            $cliente = new Client([
                                    'base_uri' => uri::RUTA_API,
                                    'exceptions' => false,
                                 ]);
            $response = $cliente->request('POST', 'servicio',
                                  ['query' => ['servicio' => $servicio, 
                            				           'valor' => $valor]]);
            if ($response->getStatusCode()==201)
              {
                $this->addFlash('correcto', 'Servicio registrado correctamente');
                return $this->redirectToRoute('servicio_nuevo');
              }
            else
              {
                $response_body =  functiones::DecoJson($response);
                $message = functiones::DecoMessage($response_body);
                $this->addFlash('incorrecto', $message );
                return $this->render('SeguroBundle:Servicios:nuevoServicio.html.twig', array('form' => $form->createView()));
              }
          }

      }
      else
      {
        return $this->render('SeguroBundle:Servicios:nuevoServicio.html.twig', array('form' => $form->createView()));
      }
    }
  }
  /**
  *@Route("generar/orden", name="servicios_lista")
  */
  public function listadoAction(){
    $rol= $this->get('session')->get('idtipousuario');
    if (($rol['tipo'] != 'ROLE_ADMIN')) {
            return $this->render('SeguroBundle:Default:index.html.twig');
    }else{  
      $cliente = new Client([ 'base_uri' => uri::RUTA_API,'exceptions' => false]);
      $response= $cliente->request('GET', 'fallecidos/orden');
      $response_body =  functiones::DecoJson($response);
      if ($response->getStatusCode()==200)
        {
          $responseServicios = $cliente->request('GET', 'servicio/listado');
          $response_servicios =  functiones::DecoJson($responseServicios);
          $responseBono = $cliente->request('GET', 'bono/listado');
          $response_bono =  functiones::DecoJson($responseBono);

          if ($responseServicios->getStatusCode()==200)
            {
              return $this->render("SeguroBundle:Servicios:Servicios.html.twig", 
                                array("datosSocio" => $response_body, 
                                      'servicios'=> $response_servicios,
                                      'bonos'=>$response_bono));
            }
            else
            {
              $message = functiones::DecoMessage($response_servicios);
              $this->addFlash('incorrecto', $message );
              return $this->render("SeguroBundle:Servicios:Servicios.html.twig", 
                                array("datosSocio" => $response_body, 
                                      'servicios'=> $response_servicios));
            }  
        }
      else
        {
          $message = functiones::DecoMessage($response_body);
          $this->addFlash('incorrecto', $message );
          return $this->render("SeguroBundle:Servicios:Servicios.html.twig");
        }
    }    
  }
  /**
  *@Route("tiempo/afiliacion", name="tiempo_afiliacion")
  */
  public function tiempoafiliacionAction(Request $request){
    $id=$request->get('idafiliado');
    $cliente = new Client([ 'base_uri' => uri::RUTA_API,'exceptions' => false]);
    $response = $cliente->request('GET', 'tiempo/afiliacion/'.$id);
    $afiliacion =  functiones::DecoJson($response);
    if ($response->getStatusCode()==200)
      {
       
       return new Response(json_encode($afiliacion),200,array('Content-Type' => 'application/json')); 
          
      }
    else
      {
        return new Response(json_encode($afiliacion),400,array('Content-Type' => 'application/json'));
      }
  }
} 