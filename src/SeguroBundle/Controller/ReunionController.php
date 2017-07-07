<?php

namespace SeguroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use SeguroBundle\Entity\Reunion;
use SeguroBundle\Form\ReunionType;
use SeguroBundle\MyClass\uri;
use SeguroBundle\MyClass\functiones;

class ReunionController extends Controller
{
/**
*@Route("nueva/reunion", name="reunion_nueva")
*/
public function indexAction(Request $request){
 
  $rol= $this->get('session')->get('idtipousuario');
  if ($rol['tipo'] != 'ROLE_ADMIN') {
          return $this->render('SeguroBundle:Default:index.html.twig');
  }else{
	$reunion = new Reunion();
  $form = $this->createForm(ReunionType::class,$reunion,array(
                'action' => $this->generateUrl('reunion_nueva'),
                'method'=> 'Post'
                ));     
  if ($request->getMethod()=="POST")
    { 
      global $respuesta;
      $form->handleRequest($request);
      if ($form->isSubmitted() and $form->isValid()) 
        {
          $temas=$form->get('temas')->getData();
          $descripcion= $form->get('descripcion')->getData();
          $valormulta=$form->get('valormulta')->getData();
          $valorreunion=$form->get('valorreunion')->getData();
          $fecha=$request->get('datetimepicker');
			    $hora=$request->get('hora');
			    $fechareunion= new \DateTime($fecha." ".$hora);
          $cliente = new Client([
                                  'base_uri' => uri::RUTA_API,
                                  'exceptions' => false,
                               ]);
          $response = $cliente->request('POST', 'reunion',
                                ['query' => ['tema' => $temas, 
                          				  'descripcion' => $descripcion,
                          				  'fechareunion' => $fechareunion->format('Y-m-d H:i:s'), 
                          			    'valorreunion' => $valorreunion, 
                          			    'valormulta' => $valormulta]]);
          if ($response->getStatusCode()==201)
            {
              $this->addFlash('correcto', 'Reunión registrada correctamente');
              return $this->redirectToRoute('reunion_nueva');
            }
          else
            {
              $response_body =  functiones::DecoJson($response);
              $message = functiones::DecoMessage($response_body);
              $this->addFlash('incorrecto', $message );
              return $this->render('SeguroBundle:Gestion:newreunion.html.twig', array('form' => $form->createView()));
            }
        }
    }
    else
    {
      return $this->render('SeguroBundle:Gestion:newreunion.html.twig', array('form' => $form->createView()));
    }
  }  
}
/**
*@Route("reunion/asistencia", name="asistencia_reunion")
*@Method({"Get"})
*/
public function reunionAction(Request $request){
   $rol= $this->get('session')->get('idtipousuario');
    if (($rol['tipo'] != 'ROLE_ADMIN')) {
            return $this->render('SeguroBundle:Default:index.html.twig');
    }else{
  	$cliente = new Client([
                       'base_uri' => uri::RUTA_API,
                       'exceptions' => false,
                      ]);
  	$responseReunion = $cliente->request('GET', 'reunion');
  	$bodyReunion = functiones::DecoJson($responseReunion);
  	$response = $cliente->request('GET', 'socios');
  	$bodySocios = functiones::DecoJson($response);
      if ($responseReunion->getStatusCode()==200)
      {
      	foreach ($bodyReunion as $key => $valueReunion) {
      		 foreach ($valueReunion as $key => $valueReunion1) {
      		 	if ($key=='idreunion')
              {
                $idreunion=$valueReunion1;
              }	
      		 }
      	}
        $responseMultas = $cliente->request('GET', 'reunion/inasistencia/'.$idreunion);
  		  $bodyMultas = functiones::DecoJson($responseMultas);
  		  if ($responseMultas->getStatusCode()==200)
    		{
  			  $arrayMultas = array();
  			  foreach ($bodyMultas as $key => $value) {
  				  foreach ($value as $key => $value1) {
  					   array_push($arrayMultas, $value1);
  				  }
  			  }
  		  }
        return $this->render("SeguroBundle:Gestion:asistencia.html.twig", 
          					compact('bodySocios','bodyReunion', 'arrayMultas'));				
      } 
      else
      {
        $message = functiones::DecoMessage($bodyReunion);
        $this->addFlash('information', $message);
        return $this->render("SeguroBundle:Gestion:asistencia.html.twig",compact('bodySocios'));   		
  	}
  }
}
/**
*@Route("reuniones/adeudadas", name="adeudadas_reuniones")
*@Method({"Get"})
*/
public function reunionesAdeudadasAction(Request $request){
    $cliente = new Client([
                       'base_uri' => uri::RUTA_API,
                       'exceptions' => false,
                      ]);
    $idafiliado = $request->get('idafiliado');
    $responseReuniones = $cliente->request('GET', 'reunion/adeuda/'.$idafiliado);
    $bodyReuniones = functiones::DecoJson($responseReuniones);
      if ($responseReuniones->getStatusCode()==200)
      {
          return new Response(json_encode($bodyReuniones), 200, array('Content-Type' => 'application/json'));
      } 
      else
      {
          return new Response(json_encode($bodyReuniones), 404, array('Content-Type' => 'application/json'));
    } 
  }
/**
  * @Route("reunion/pago", name="pago_reunion")
*/
public function pagoReunionAction(Request $request)
{
  if ($request->isXmlHttpRequest())
    {
      $valor=$request->get('valor');
      $idusuario=$request->get("idusuario");
      $idafiliado=$request->get('idafiliado');
      $idreunion = $request->get('idreunion');
      $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
      $response = $cliente->request('POST', 'reunion/pagar',
                            ['query' => [
                                        'valor' => $valor,
                                        'idusuario' => $idusuario,
                                        'idafiliado' => $idafiliado,
                                        'idreunion' => $idreunion]
                            ]);
      $bodyReunion = functiones::DecoJson($response);
      if ($response->getStatusCode()==200)
        { 
          return new Response(json_encode($bodyReunion),200,array('Content-Type' => 'application/json'));
        } 
      else
        {
          return new Response(json_encode($bodyReunion),400,array('Content-Type' => 'application/json'));
        }
    }
  } 
}