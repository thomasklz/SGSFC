<?php
namespace SeguroBundle\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use SeguroBundle\Entity\Ingreso;
use SeguroBundle\Entity\Mes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class CuotasApiController extends FOSRestController {
	
   /**
     * you can get a list of the months owed the socio by year
     * @ApiDoc(
     *     section = "Quotas : Quotas management",
     *     description="Returns the months owed the socio by year",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    requirements={
     *        {"name"="year", "requirement"="true", "dataType"="integer", "description"="to enter the id_reunion"},
     *        {"name"="idafiliado", "requirement"="true", "dataType"="integer", "description"="to enter the id_reunion"}
     *    }   
     * )
    * @Rest\Get("/meses/pagar/{year}/{idafiliado}")
  */
   //Obtener mesess a pagar por año
  public function mesesadeudadosAction($year, $idafiliado)
    {
      $afiliado = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                      ->findOneBy(array('idafiliado' => $idafiliado)); 
      if(!$afiliado)
        {
          $datos = array(
                    'code' => 404,
                    'message' => 'Socio no encontrado'
                  );
          return new View($datos, Response::HTTP_NOT_FOUND); 
        }  
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT m.idmes, m.mes, m.valor, m.fecha  
                                  FROM SeguroBundle:Mes  m  
                                  WHERE  YEAR(m.fecha)= :year and not exists (SELECT i.idingreso FROM SeguroBundle:Ingreso i WHERE i.idmes= m.idmes and i.idafiliado= :idafiliado)");
      $query->setParameters(array(
                             'idafiliado' => $idafiliado,
                              'year' => $year)
                            );
      $mesesPagar = $query->getResult(); 
      if (empty($mesesPagar)) {
          $datos = array(
             'code' => 404,
             'message' => 'No adeduda mes'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $mesesPagar;
    }
 
  /**
     * you can get the year of the months
     * @ApiDoc(
     *     section = "Quotas : Quotas management",
     *     description="Returns the year of the month",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
    * @Rest\Get("/mes/year")
  */
   //Obtener años de meses ingresados
  public function mesesyearAction()
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT distinct (YEAR(m.fecha)) as year  
                                  FROM SeguroBundle:Mes  m  ");
      $meses = $query->getResult(); 
      if (empty($meses)) {
          $datos = array(
             'code' => 404,
             'message' => 'No existen meses ingresados'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $meses;
    } 
 
  /**
     * You can to pay the quotas of the socio
     * @ApiDoc(
     *     section = "Quotas : Quotas management",
     *     description="Pay the quota of the socio",
     *     statusCodes={
     *         201="Returned when is create successful",
     *         400="Returned when is bad request",
     *         404="Returned when is not found",
     *         422="Returned when is unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    parameters={
     *        {"name"="valor", "dataType"="double", "required"="true", "description"="to enter the value to pay"},
     *        {"name"="idusuario", "dataType"="integer", "required"="true", "description"="to enter the id_usuario"},
     *        {"name"="idafiliado", "dataType"="integer", "required"="true", "description"="to enter the id_afiliado"},
     *        {"name"="idmes", "dataType"="array", "required"="true", "description"="to enter the id_mes"}
     *    }
     * )
   * @Rest\Post("/meses/pagar")
  */
  //Pago de meses  
  public function mesespagarAction(Request $request)
    {
      $valor= $request->get("valor");
      $idUsuario=$request->get("idusuario");
      $idafiliado=$request->get("idafiliado");
      $idmes=$request->get("idmes");
      if((empty($valor)) || (empty($idUsuario)) || (empty($idafiliado)) || (empty($idmes)) )
          {
            $datos = array(
                    'code' => 422,
                    'message' => 'No se permiten valores nulos'
                  );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }
      $usuario = $this->getDoctrine()->getRepository('SeguroBundle:Usuario')
                      ->findOneBy(array('idusuario' => $idUsuario)); 
      if (empty($usuario)) {
          $datos = array(
             'code' => 404,
             'message' => 'Usuario no encontrado'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
      }               
      $tipoIngreso = $this->getDoctrine()->getRepository('SeguroBundle:Tipoingreso')
                      ->findOneBy(array('idtipoingreso' => 1));  
      $afiliado = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                      ->findOneBy(array('idafiliado' => $idafiliado));
      if (empty($afiliado)) {
          $datos = array(
             'code' => 404,
             'message' => 'Socio no encontrado'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
      }
      if(!(is_array($idmes)))
        {
          $datos = array(
                    'code' => 422,
                    'message' => 'Error, se esperaba un array con los id_meses a pagar'
                  );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
      }  
      foreach ($idmes as $key => $mesValor) {
       $mesid = $this->getDoctrine()->getRepository('SeguroBundle:Mes')
                      ->findOneBy(array('idmes' => $mesValor));                                 
        $mes= new Ingreso();
        $mes->setValor($valor);
        $mes->setFechaingreso(new \DateTime());
        $mes->setIdusuario($usuario);
        $mes->setIdtipoingreso($tipoIngreso);
        $mes->setIdafiliado($afiliado);
        $mes->setIdmes($mesid);
        $em = $this->getDoctrine()->getManager();
        $em->persist($mes);
        $em->flush();
      }
      $data=array(
                  'code' => 200,
                  'message' => 'Pago realizado correctamente'
                  );
      return new View($data, Response::HTTP_OK);
    }
  
  /**
     * You can to register new months
     * @ApiDoc(
     *     section = "Quotas : Quotas management",
     *     description="Register new month",
     *     statusCodes={
     *         201="Returned when is create successful",
     *         400="Returned when is bad request",
     *         422="Returned when is unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    parameters={
     *        {"name"="valor", "dataType"="double", "required"="true", "description"="to enter the value"},
     *        {"name"="mes", "dataType"="array", "required"="true", "description"="to enter all the month of the year"}
     *    }
     * )
   * @Rest\Post("meses/")
  */
  //Registrar los nuevos meses  
  public function mesesNuevoAction(Request $request)
    {
      $valor= $request->get("valor");
      $meses=$request->get("mes");    
      if((empty($meses)) || (empty($valor)))
          {
            $datos = array(
                    'code' => 422,
                    'message' => 'No se permiten valores nulos'
                  );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }
       if(!(is_array($meses)))
          {
            $datos = array(
                    'code' => 422,
                    'message' => 'Error, se esperaba un array de meses'
                  );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT m.idmes
                                  FROM SeguroBundle:Mes  m  
                                  WHERE  YEAR(m.fecha)= YEAR(now()) ");
      $mesesRes = $query->getResult();
      if($mesesRes){
        $datos = array(
                    'code' => 400,
                    'message' => 'Ya se encuentran registrados los meses para este año'
                  );
        return new View($datos, Response::HTTP_BAD_REQUEST); 
      }

      foreach ($meses as $key => $mesValor) {
          $mes= new Mes();
          $mes->setValor($valor);
          $mes->setFecha(new \DateTime());
          $mes->setMes($mesValor);
          $em = $this->getDoctrine()->getManager();
          $em->persist($mes);
          $em->flush();
        }              
      $data=array(
                  'code' => 201,
                  'message' => 'Meses registrados correctamente'
                  );
      return new View($data, Response::HTTP_CREATED);
    }
}