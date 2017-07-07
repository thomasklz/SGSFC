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

class SocioController extends Controller
{
	/**
	* @Route("socio/nuevo", name="nuevo_socio")
	*/
	public function nuevoAction(Request $request){
    $rol= $this->get('session')->get('idtipousuario');
      if (($rol['tipo'] != 'ROLE_ADMIN') && ($rol['tipo'] != 'ROLE_COBRADOR')) {
              return $this->render('SeguroBundle:Default:index.html.twig');
      }else{
  		if($request->getMethod()=="POST")
  		{
  			$nombre= $request->get("nombres");
  			$apellido=$request->get("apellidos");
  			$cedula=$request->get("cedula");
  			$fechanacimiento= new \DateTime($request->get("datetimepicker"));
  			$sexo=$request->get("sexo");
  			$parentesco=$request->get("parentesco");
  			$tipoafiliacion=$request->get("tipoafiliacion");
  			$cliente = new Client([
                       'base_uri' => uri::RUTA_API,
                       'exceptions' => false,
                      ]);
        $response = $cliente->request('POST', 'socio/',
                              ['query' => [
                              			'nombre' => $nombre,
                									 	'apellido' => $apellido,
                									  'cedula' => $cedula,
                									  'fechanacimiento' => $fechanacimiento->format('Y-m-d'),
                									  'sexo' => $sexo,
                									  'parentesco' => $parentesco,
                									  'tipoafiliacion' => $tipoafiliacion]
  							              ]);
        $response_body = functiones::DecoJson($response);
        if ($response->getStatusCode()==201)
        { 
          $responseUser = $cliente->request('POST', 'usuario/',
                            ['query' =>
                                   ['usuario' => $cedula, 
                                    'password' => $cedula,
                                    'idtipousuario' => 3,
                                    'idafiliado' => $response_body[0]['idafiliado']]]);
          $this->addFlash('CorrectSocio', "Socio registrado correctamente");
          return $this->render("SeguroBundle:Beneficiario:nuevoBeneficiario.html.twig", array('cedulaSocio' => $cedula )); 
        } 
        else
        {
         // $this->addFlash('ErrorSocio', $response_body['message']);
          return $this->render('SeguroBundle:Socio:nuevosocio.html.twig');	
  			}    
  		}
  		else
  		{
  			return $this->render("SeguroBundle:Socio:nuevosocio.html.twig");
  		}
    }  
	}
	/**
	* @Route("socio/listado", name="listado_socio")
	*/
	public function listAction(Request $request)
  {
		 $rol= $this->get('session')->get('idtipousuario');
      if (($rol['tipo'] != 'ROLE_ADMIN') && ($rol['tipo'] != 'ROLE_COBRADOR')) {
              return $this->render('SeguroBundle:Default:index.html.twig');
      }else{	
      $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
      $response = $cliente->request('GET', 'socios');
      $response_body = functiones::DecoJson($response);
        if ($response->getStatusCode()==200)
        	{
            return $this->render("SeguroBundle:Socio:listSocio.html.twig", array('datosSocio' => $response_body));                   
          } 
        else
          {
            $message = functiones::DecoMessage($response_body);
            $this->addFlash('SocioNotFound', $message);
            return $this->render("SeguroBundle:Socio:listSocio.html.twig");   		
			    }
     }     
	}
  /**
  * @Route("search/socio", name="socio_search")
  * @Method({"Post"})
  */
  public function searchsocioAction(Request $request){
    if ($request->isXmlHttpRequest())
        {
          $cedula=$request->get('cedula');
          $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
          $response = $cliente->request('POST', 'socio/search/',
                                ['query' => [ 'cedula' => $cedula]]);
          $response_body = functiones::DecoJson($response);
          if ($response->getStatusCode()==200)
            { 
              return new Response(json_encode($response_body),200,array('Content-Type' => 'application/json'));
            } 
          else
            {
               return new Response(json_encode($response_body),404, array('Content-Type' => 'application/json'));
            }
        }
    }
  /**
  * @Route("search/socio/afiliado", name="socio_afiliado_search")
  * @Method({"Post"})
  */
  public function searchsocioafiliadoAction(Request $request){
    if ($request->isXmlHttpRequest())
        {
          $cedula=$request->get('cedula');
          $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
          $response = $cliente->request('POST', 'socio/afiliado/search/',
                                ['query' => [ 'cedula' => $cedula]]);
          $response_body = functiones::DecoJson($response);
          if ($response->getStatusCode()==200)
            { 
              return new Response(json_encode($response_body),200,array('Content-Type' => 'application/json'));
            } 
          else
            {
               return new Response(json_encode($response_body),404, array('Content-Type' => 'application/json'));
            }
        }
    } 
  /**
  * @Route("socio/actualizar/{id}", name="actualizar_socio")
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
            return $this->redirectToRoute('pagina_principal');  
  
          } 
        else
          {
            $message = functiones::DecoMessage($response_body);
            $this->addFlash('incorrecto', $message);
            return $this->redirectToRoute('pagina_principal');   
          } 
  } 
  /**
  *@Route("socio/cambiar/{ids}/{ida}", name="socio_cambiar")
  */
  public function cambiarSocioAction(Request $request, $ids, $ida){ 
    $cliente = new Client([ 'base_uri' => uri::RUTA_API,'exceptions' => false]);
    $updateSocio = $cliente->request('PUT', 'socio/cambio/'.$ids.'/'.$ida);
    if (($updateSocio->getStatusCode()==200))
      {                                    
        $this->addFlash('correcto', 'Cambio realizado correctamente');
        return $this->redirectToRoute('listado_socio');   
      }
    else
      {
        $this->addFlash('incorrecto', 'No se pudo realizar el cambio');
        return $this->redirectToRoute('listado_socio');   
      } 
  }
}