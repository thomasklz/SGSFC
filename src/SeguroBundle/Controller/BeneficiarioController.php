<?php

namespace SeguroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use SeguroBundle\Entity\Afiliado;
use SeguroBundle\MyClass\uri;
use SeguroBundle\MyClass\functiones;

class BeneficiarioController extends Controller
{
  /**
  * @Route("beneficiario/nuevo", name="nuevo_beneficiario")
  */
  public function listAction()
    {
      $rol= $this->get('session')->get('idtipousuario');
      if (($rol['tipo'] != 'ROLE_ADMIN') && ($rol['tipo'] != 'ROLE_COBRADOR')) {
              return $this->render('SeguroBundle:Default:index.html.twig');
      }else{
        return $this->render('SeguroBundle:Beneficiario:nuevoBeneficiario.html.twig');
      }
    }
  /**
  * @Route("nuevo/afiliado", name="afiliado_nuevo")
  * @Method({"Post"})
  */
  public function nuevoafiliadoAction(Request $request){
      if ($request->isXmlHttpRequest())
        {
          $nombre=$request->get('nombre');
          $apellido=$request->get("apellido");
          $cedula=$request->get('cedula');
          $fechanacimiento= new \DateTime($request->get("fechanacimiento"));
          $sexo=$request->get("sexo");
          $parentesco=$request->get('parentesco');
          $tipoafiliacion=$request->get("tipoafiliacion");
          $idafiliacionedependiente=$request->get('idafiliacionedependiente');
          $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
          $response = $cliente->request('POST', 'beneficiario/',
                            ['query' => [
                                        'nombre' => $nombre,
                                        'apellido' => $apellido,
                                        'cedula' => $cedula,
                                        'fechanacimiento' =>$fechanacimiento->format('Y-m-d'),
                                        'sexo' => $sexo,
                                        'parentesco' => $parentesco,
                                        'tipoafiliacion' => $tipoafiliacion,
                                        'idafiliacionedependiente' => $idafiliacionedependiente]
                            ]);
          $response_body = functiones::DecoJson($response);
          if ($response->getStatusCode()==201)
          { 
            return new Response(
                    json_encode(array('message' => 'Afiliado registrado correctamente')),
                    201,
                    array('Content-Type' => 'application/json')
                );
          } 
          else
          {
            return new Response(json_encode($response_body),404,array('Content-Type' => 'application/json'));
          }
        }  
    } 
	/**
	* @Route("socio/beneficiarios", name="beneficiarios_socio")
	* @Method ({"Get"})
	*/
	public function beneficiariosAction(Request $request)
  {
	  
    $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
    if($request->isXmlHttpRequest())
      {
		    $id=$request->get('id');
        $response = $cliente->request('GET', 'socio/'.$id.'/afiliados');
        $response_body = functiones::DecoJson($response);        
        if ($response->getStatusCode()==200)
        	{
            return new Response(json_encode($response_body),200, array('Content-Type' => 'application/json'));
          } 
        else
          {
            return new Response(json_encode($response_body),400, array('Content-Type' => 'application/json'));
          }
			}else{
        $idafiliado= $this->get('session')->get('idafiliado');
        $response = $cliente->request('GET', 'socio/'.$idafiliado.'/afiliados');
        $response_body = functiones::DecoJson($response); 
        if ($response->getStatusCode()==200)
          {
            return $this->render("SeguroBundle:Beneficiario:misBeneficiarios.html.twig", 
                                  array("beneficiarios" => $response_body));
          } else{
            $this->addFlash('incorrecto', $response_body['message']);
            return $this->render("SeguroBundle:Beneficiario:misBeneficiarios.html.twig");
          }
      }
  }
  /**
  * @Route("socio/beneficiarios/fallecidos", name="beneficiariossocio_fallecidos")
  * @Method ({"Get"})
  */
  public function fallecidosAction()
  {
     $rol= $this->get('session')->get('idtipousuario');
    if (($rol['tipo'] != 'ROLE_ADMIN')) {
            return $this->render('SeguroBundle:Default:index.html.twig');
    }else{
      $response_body= $this->fallecios();
      return $this->render("SeguroBundle:Gestion:listaFallecidos.html.twig", array("datosSocio" => $response_body));
    }  
  }
  public function fallecios(){
    $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);
    $response = $cliente->request('GET', 'socios/afiliados/fallecidos');
    $response_body = functiones::DecoJson($response);        
    if ($response->getStatusCode()==200)
      {
        return  $response_body;
      } 
    else
      {
        $message = functiones::DecoMessage($response_body);
        $this->addFlash('incorrecto', $message);
        return $message;
      }
  }
  /**
  * @Route("socio/beneficiarios/estado/fallecidos/{id}", name="estado_fallecidos")
  */
  public function fallecidosestadoAction(Request $request, $id)
  {
   $estado= $request->get('estado');
   $fechafallecido= new \DateTime();
   $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);
   $response = $cliente->request('PUT', 'socios/afiliados/'.$id.'/fallecidos',
                            ['query' => [
                                        'estado' => $estado,
                                        'fechafallecido' => $fechafallecido->format('Y-m-d')
                                        ]
                            ]);
    $response_body = functiones::DecoJson($response);        
    if ($response->getStatusCode()==200)
      {
        $this->addFlash('correcto', 'Estado actualizado correctamente');
        return $this->redirectToRoute('beneficiariossocio_fallecidos');
      } 
    else
      {
        $message = functiones::DecoMessage($response_body);
        $this->addFlash('incorrecto', $message);
        return $this->redirectToRoute('beneficiariossocio_fallecidos');
      }
  }
  /**
  * @Route("lista/beneficiario/editar/{id}", name="editar_beneficiario")
  */
  public function listbeneAction(Request $request, $id)
  {
      $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);
      $response = $cliente->request('GET', 'beneficiario/'.$id);
      $response_body = functiones::DecoJson($response);
        if ($response->getStatusCode()==200)
          {
            return $this->render("SeguroBundle:Beneficiario:editBeneficiario.html.twig", 
                                    array('beneficiario' => $response_body));
          } 
        else
          {
            $message = functiones::DecoMessage($response_body);
            $this->addFlash('incorrecto', $message);
            return $this->render("SeguroBundle:Beneficiario:editBeneficiario.html.twig");       
          }
  }
   /**
  * @Route("beneficiario/actualizar/{id}", name="actualizar_beneficiario")
  */
  public function actualizarAction(Request $request, $id){
   
      $nombre= $request->get("nombres");
      $apellido=$request->get("apellidos");
      $cedula=$request->get("cedula");
      $fechanacimiento= new \DateTime($request->get("datetimepicker"));
      $sexo=$request->get("sexo");
      $parentesco=$request->get("parentesco");
      $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
      $response = $cliente->request('PUT', 'socio/'.$id,
                            ['query' => [
                                  'nombre' => $nombre,
                                  'apellido' => $apellido,
                                  'cedula' => $cedula,
                                  'fechanacimiento' => $fechanacimiento->format('Y-m-d'),
                                  'sexo' => $sexo,
                                  'parentesco' => $parentesco]
                            ]);
      $response_body = functiones::DecoJson($response);
        if ($response->getStatusCode()==200)
          { 
            $this->addFlash('correcto', 'Datos actualizado correctamente');
            return $this->redirectToRoute('beneficiarios_socio'); 
          } 
        else
          {
            $message = functiones::DecoMessage($response_body);
            $this->addFlash('incorrecto', $message);
            return $this->redirectToRoute('editar_beneficiario', array('id' => $id));   
          } 
  }
 }