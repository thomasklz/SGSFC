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

class CobrosController extends Controller
{
/**
 * @Route("cobros", name="cobros_index")
*/
public function indexAction()
 {
  $rol= $this->get('session')->get('idtipousuario');
    if (($rol['tipo'] == 'ROLE_ADMIN') || ($rol['tipo'] == 'ROLE_COBRADOR')) {
        $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);
        $response = $cliente->request('GET', 'mes/year');
        $response_body = functiones::DecoJson($response);
        if ($response->getStatusCode()==200){
         return $this->render('SeguroBundle:Gestion:index.html.twig', array('year'=>$response_body));
        }else{
          return $this->render('SeguroBundle:Gestion:index.html.twig');
        }  
    }else{
        return $this->render('SeguroBundle:Default:index.html.twig');
  }  
 }
/**
 * @Route("meses/pago", name="pago_meses")
*/
public function pagomesAction(Request $request){
  if ($request->isXmlHttpRequest())
    {
      $valor=$request->get('valor');
      $idusuario=$request->get("idusuario");
      $idafiliado=$request->get('idafiliado');
      $idmes = json_decode(stripslashes($_POST['idmes']));
      $arrayName = array();
      foreach ($idmes as $key => $value1) {
             array_push($arrayName, $value1);
          } 
      $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);
      $response = $cliente->request('POST', 'meses/pagar',
                                             ['query' => [
                                                            'valor' => $valor,
                                                            'idusuario' => $idusuario,
                                                            'idafiliado' => $idafiliado,
                                                            'idmes' => $arrayName]
                                     ]);
       if ($response->getStatusCode()==200)
        { 
          return new Response(json_encode(array('message' => 'Pago realizado con éxito')),
                                200,  array('Content-Type' => 'application/json')
                             );
        } 
       else
        {
          return new Response(json_encode(array('message' => 'Error al realizar el pago')),
                                404,array('Content-Type' => 'application/json')
                             );
        }
    }
}
  /**
  * @Route("meses/adeudados", name="adeudados_meses")
  * @Method({"Get"})
  */
  public function mesesadeudadosAction(Request $request){
    if ($request->isXmlHttpRequest())
    {
      $year=$request->get('year');
      $idafiliado=$request->get('idafiliado');
      $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
      $response = $cliente->request('GET', 'meses/pagar/'.$year.'/'.$idafiliado);
      $response_body = functiones::DecoJson($response);
      if ($response->getStatusCode()==200)
       { 
         return new Response(json_encode($response_body),200,array('Content-Type' => 'application/json'));
       } 
     else
      {
        return new Response(json_encode($response_body), 404, array('Content-Type' => 'application/json'));
      }
    }
  }
  /**
  * @Route("nuevo/meses", name="nuevo_meses")
  */
  public function nuevomesesAction(Request $request){
    $rol= $this->get('session')->get('idtipousuario');
    if ($rol['tipo'] != 'ROLE_ADMIN') {
            return $this->render('SeguroBundle:Default:index.html.twig');
    }else{
     if ($request->getMethod()=="POST")
      { 
        $valor=$request->get('valor');
        if(is_numeric($valor)){
          $meses=  array(1=>"Enero", 2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",7=>"Julio",8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre");
          $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);
          $response = $cliente->request('POST', 'meses/',
                                                ['query' => [
                                                            'valor' => $valor,
                                                            'mes' => $meses]]);
          $response_body = functiones::DecoJson($response);
          if ($response->getStatusCode()==201)
          { 
            $this->addFlash('correcto', "Meses ingresados correctamente");
            return $this->render("SeguroBundle:Gestion:meses.html.twig");
          } 
          else
          {
            $this->addFlash('incorrecto', $response_body['message']);
            return $this->render("SeguroBundle:Gestion:meses.html.twig");
          }
        }else{
           $this->addFlash('xnumero', "Solo se aceptan números");
           return $this->render("SeguroBundle:Gestion:meses.html.twig");
        }

      }else{
          return $this->render("SeguroBundle:Gestion:meses.html.twig");
      }
    }  
  }
/**
 * @Route("reporte/ingreso", name="reporte_ingresos")
*/
 public function generarReportAction(Request $request)
 {
    $rol= $this->get('session')->get('idtipousuario');
    if ($rol['tipo'] != 'ROLE_ADMIN') {
            return $this->render('SeguroBundle:Default:index.html.twig');
    }else{
    if ($request->getMethod()=="POST")
      {
        $fecha= $request->get('datetimepicker');
        $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);
        $response = $cliente->request('GET', 'ingresos',
                                                    ['query' => ['fecha' => $fecha]]);
        $reportes = functiones::DecoJson($response);
        if ($response->getStatusCode()==200){
         return $this->render('SeguroBundle:Report:ReportIngresos.html.twig', array('reportes'=>$reportes));
        }else{
          $this->addFlash('incorrecto', $reportes['message']);
          return $this->render('SeguroBundle:Report:ReportIngresos.html.twig');
        }
      }else{
        return $this->render('SeguroBundle:Report:ReportIngresos.html.twig');
      }
    }  
  }
/**
 * @Route("reporte/egreso", name="reporte_egresos")
*/
 public function reportEgresoAction(Request $request)
 {
  $rol= $this->get('session')->get('idtipousuario');
  if ($rol['tipo'] != 'ROLE_ADMIN') {
          return $this->render('SeguroBundle:Default:index.html.twig');
  }else{
    if ($request->getMethod()=="POST")
      {
        $fecha= $request->get('datetimepicker');
        $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);
        $response = $cliente->request('GET', 'egresos',
                                                    ['query' => ['fecha' => $fecha]]);
        $reportes = functiones::DecoJson($response);
        if ($response->getStatusCode()==200){
         return $this->render('SeguroBundle:Report:ReportEgresos.html.twig', array('egresos'=>$reportes));
        }else{
          $this->addFlash('incorrecto', $reportes['message']);
          return $this->render('SeguroBundle:Report:ReportEgresos.html.twig');
        }
      }else{
        return $this->render('SeguroBundle:Report:ReportEgresos.html.twig');
      }
    }  
  }
  /**
  * @Route("reporte/deudas/socio", name="reporte_deudasSocio")
  */
  public function reportDeudasAction(Request $request)
  {
    $year= $request->get('year');
    if(empty($year)){
      $year= date('Y');
    }
    $idafiliado=$this->get('session')->get('idafiliado');
    $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);
    $responseMultas = $cliente->request('GET', 'reunion/multas/'.$idafiliado);
    $bodyMultas = functiones::DecoJson($responseMultas);
    $responseReuniones = $cliente->request('GET', 'reunion/adeuda/'.$idafiliado);
    $bodyReuniones = functiones::DecoJson($responseReuniones);
    $responseMeses = $cliente->request('GET', 'meses/pagar/'.$year.'/'.$idafiliado);
    $bodyMeses = functiones::DecoJson($responseMeses);
    $responseYears = $cliente->request('GET', 'mes/year');
    $bodyYears = functiones::DecoJson($responseYears);

    if (($responseMultas->getStatusCode()==200) || ($responseReuniones->getStatusCode()==200) || 
        ($responseMeses->getStatusCode()==200) || ($responseYears->getStatusCode()==200)) {
            return $this->render('SeguroBundle:Report:MRMadeudados.html.twig', 
                              array('multas'=>$bodyMultas,
                                    'reuniones'=>$bodyReuniones,
                                    'meses'=>$bodyMeses,
                                    'year'=>$bodyYears));
      }else{
          return $this->render('SeguroBundle:Report:MRMadeudados.html.twig');
      }
  }
}