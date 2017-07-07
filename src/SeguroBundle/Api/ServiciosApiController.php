<?php
namespace SeguroBundle\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use SeguroBundle\Entity\Servicio;
use SeguroBundle\Entity\OredenServicio;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ServiciosApiController extends FOSRestController {
    
  /**
     * You can register news services 
     * @ApiDoc(
     *     section = "Services : Services management",
     *     description="Register new service",
     *     statusCodes={
     *         201="Returned when is create successful",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *      {"name"="servicio", "dataType"="string", "required"="true", "description"="to enter new service"},
     *      {"name"="valor", "dataType"="double", "required"="true", "description"="to enter value of the service"}
     *  }
     * )
   * @Rest\Post("/servicio")
  */
  //Ingresar servicios
  public function nuevoAction(Request $request)
    {
      $servicioName= $request->get("servicio");
      $valor=$request->get("valor");
      if((empty($servicioName)) || (empty($valor)))
          {
            $datos = array(
                    'code' => 422,
                    'message' => 'No se permiten valores nulos'
                  );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }    
      $servicio= new Servicio();    
      $servicio->setServicio($servicioName);
      $servicio->setValor($valor);
      $servicio->setFechaingreso(new \DateTime());
      $servicio->setEstado(1);
      $em = $this->getDoctrine()->getManager();
      $em->persist($servicio);
      $em->flush();
      $data=array(
                  'code' => 201,
                  'message' => 'Servicio ingresado correctamente'
                  );
      return new View($data, Response::HTTP_CREATED);
    }
 
  /**
     * You can list all servicios
     * @ApiDoc(
     *     section = "Services : Services management",
     *     description="Return a list of services",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
    * @Rest\Get("/servicio/listado")
  */
   //Obtener listado de servicios  
  public function listadoAction()
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT s.idservicio, s.servicio, s.valor, s.fechaingreso, s.estado  
                                  FROM SeguroBundle:Servicio  s ");
      $servicios = $query->getResult(); 
      if (empty($servicios)) {
          $datos = array(
             'code' => 404,
             'message' => 'No existen servicios'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $servicios;
    }
 
  /**
     * You can calculate the membership time
     * @ApiDoc(
     *     section = "Socios : Socios management",
     *     description="Return the membership time",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  requirements={
     *      {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_socio"}
     *  }
     * )
    * @Rest\Get("/tiempo/afiliacion/{id}")
  */
  //Calcular tiempo de afiliación  
  public function tiempoAction($id)
    {
      $afiliado = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                      ->findOneBy(array('idafiliado' => $id)); 
      if(!$afiliado)
        {
          $datos = array(
                    'code' => 404,
                    'message' => 'Socio no encontrado'
                  );
          return new View($datos, Response::HTTP_NOT_FOUND); 
        } 
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT round(DATEDIFF(now(), a.fechaingreso)/30.41) AS meses  
                                  FROM SeguroBundle:Afiliado a
                                  WHERE a.idafiliado= :idafiliado");
      $query->setParameters(array(
          'idafiliado' => $id
      ));
      $tiempo = $query->getResult(); 
      if (empty($tiempo)) {
          $datos = array(
             'code' => 404,
             'message' => 'No tiene ningún mes de ingreso'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $tiempo;
    }
  
   /**
     * You can register services on orden
     * @ApiDoc(
     *     section = "Services : Services management",
     *     description="Register services on orden",
     *     statusCodes={
     *         200="Returned when is successful",
     *         404="Returned when is not found",     
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *      {"name"="idorden", "dataType"="integer", "required"="true", "description"="to enter the id_orden"},
     *      {"name"="idservicio", "dataType"="array", "required"="true", "description"="to enter the id_servicio"}
     *  }
     * )
    * @Rest\Post("/orden/servicios")
  */
  //Ingresar servicios a la orden  
  public function nuevoServiciosAction(Request $request)
    {
      $arrayName = array();
      $idorden=$request->get('idorden');
      $idservicio=$request->get('idservicio');
  
      if((empty($idorden)) || (empty($idservicio)))
          {
            $datos = array(
                      'code' => 422,
                      'message' => 'No se permiten valores nulos'
                      );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }
      foreach ($idservicio as $key => $value1) {
             array_push($arrayName, $value1);
          }   
      $orden = $this->getDoctrine()->getRepository('SeguroBundle:Orden')
                   ->findOneBy(array('idorden' => $idorden));
      if (empty($orden)) {
          $datos = array(
             'code' => 404,
             'message' => 'Orden no existente'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
       foreach ($arrayName as $key => $servicio1) {
            $servicio = $this->getDoctrine()->getRepository('SeguroBundle:Servicio')
                   ->findOneBy(array('idservicio' => $servicio1));
            $ordenService = new OredenServicio();
            $ordenService->setIdorden($orden);
            $ordenService->setIdservicio($servicio);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ordenService);
            $em->flush();
          }  
        $datos = array(
                      'code' => 200,
                      'message' => 'Servicios ingresados correctamente en la orden'
                    );
        return new View($datos, Response::HTTP_OK);
    }
   
   /**
     * You can calculate the membership time
     * @ApiDoc(
     *     section = "Services : Services management",
     *     description="Return the membership time",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  requirements={
     *      {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_orden"}
     *  }
     * )
    * @Rest\Get("/orden/servicios/{id}")
  */
  //Lista de orden servicios  
  public function ServiciosonOrdenAction($id)
    {
      $orden = $this->getDoctrine()->getRepository('SeguroBundle:Orden')
                   ->findOneBy(array('idorden' => $id));
      if (empty($orden)) {
          $datos = array(
             'code' => 404,
             'message' => 'Orden no existente'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT o.idorden, s.servicio, s.valor
                                  FROM SeguroBundle:OredenServicio  os  join os.idservicio s join os.idorden o
                                  WHERE os.idorden= :orden ");
      $query->setParameters(array('orden' => $id));
      $orden = $query->getResult();  
      if (empty($orden)) {
          $datos = array(
             'code' => 404,
             'message' => 'No se encontró ningún servicios para esta orden'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }  
      return new View($orden, Response::HTTP_OK);
    }         
}