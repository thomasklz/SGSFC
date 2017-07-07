<?php
namespace SeguroBundle\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class BonoApiController extends FOSRestController {
  
  /**
     * you can get the list of bonos register
     * @ApiDoc(
     *     section = "Bonds : Bonds management",
     *     description="Returns list of bonds register",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
    * @Rest\Get("/bono/listado")
  */
  //Obtener listado de bono  
  public function listadoBonoAction()
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT b.idbono, b.bono, b.fechaingreso, b.valor, b.estado  
                                  FROM SeguroBundle:Bono  b ORDER BY b.idbono desc ");
      $bono = $query->getResult(); 
      if (empty($bono)) {
          $datos = array(
             'code' => 404,
             'message' => 'No existen bonos ingresados'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $bono;
    }
  
  /**
     * you can to list the bonds of the orders
     * @ApiDoc(
     *     section = "Bonds : Bonds management",
     *     description="Returns the bond by id_orden",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    requirements={
     *        {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_orden"}
     *    }   
     * )
    * @Rest\Get("/orden/bono/{id}")
  */
  //Lista de orden bono  
  public function listBonoAction($id)
    {
      $orden = $this->getDoctrine()->getRepository('SeguroBundle:Orden')
                   ->findOneBy(array('idorden' => $id));
      if (empty($orden)) {
          $datos = array(
             'code' => 404,
             'message' => 'Orden no encontrada'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT o.idorden, b.bono
                                  FROM SeguroBundle:orden  o  join o.idbono b
                                  WHERE o.idorden= :orden ");
      $query->setParameters(array('orden' => $id));
      $orden = $query->getResult();  
      if (empty($orden)) {
          $datos = array(
             'code' => 404,
             'message' => 'No se encontró bono para esta orden'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }  
      return new View($orden, Response::HTTP_OK);
    } 
  
  /**
     * You can get a report by day, month and year of the income realized
     * @ApiDoc(
     *     section = "Bonds : Bonds management",
     *     description="Returns the income realized by day, month and year",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    parameters={
     *        {"name"="fecha", "dataType"="date", "required"="true", "description"="to enter the date"}
     *    }
     * )
    * @Rest\Get("ingresos")
  */
  //Ingresos del día, mes y año por fecha  
  public function totalIngresoAction(Request $request)
  {
    $fecha= $request->get('fecha');
    if (empty($fecha)) {
          $datos = array(
             'code' => 404,
             'message' => 'No se permiten campos vacíos'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
    }
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery("SELECT  distinct
                                (select sum(ig.valor)  from SeguroBundle:Ingreso ig where date(ig.fechaingreso)= date(:fecha))  as TotalDia, 
                                (select sum(igr.valor)  from SeguroBundle:Ingreso igr where month(igr.fechaingreso)=month(:fecha)) as TotalMes,
                                (select sum(igre.valor)  from SeguroBundle:Ingreso igre where year(igre.fechaingreso)=year(:fecha)) as TotalYear
                                 FROM SeguroBundle:Ingreso i ");
    $query->setParameters(array('fecha' => $fecha));
    $ingresos = $query->getResult();  
    if (empty($ingresos)) {
          $datos = array(
             'code' => 404,
             'message' => 'No existen ingresos'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
      }  
    return new View($ingresos, Response::HTTP_OK);
  } 
  
  /**
     * You can get a report by day, month and year of the expenses realized
     * @ApiDoc(
     *     section = "Bonds : Bonds management",
     *     description="Returns the  expenses realized by day, month and year",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    parameters={
     *        {"name"="fecha", "dataType"="date", "required"="true", "description"="to enter the date"}
     *    }
     * )
    * @Rest\Get("egresos")
  */
  //Egresos del día, mes y año por fecha  
  public function totalEgresosAction(Request $request)
  {
    $fecha= $request->get('fecha');
    if (empty($fecha)) {
          $datos = array(
             'code' => 404,
             'message' => 'No se permiten campos vacíos'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
    }
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery("SELECT  distinct
                                (select sum(eg.valor)  from SeguroBundle:Egreso eg where date(eg.fechaegreso)= date(:fecha))  as TotalDia, 
                                (select sum(egr.valor)  from SeguroBundle:Egreso egr where month(egr.fechaegreso)=month(:fecha)) as TotalMes,
                                (select sum(egre.valor)  from SeguroBundle:Egreso egre where year(egre.fechaegreso)=year(:fecha)) as TotalYear
                                 FROM SeguroBundle:Egreso e ");
    $query->setParameters(array('fecha' => $fecha));
    $egresos = $query->getResult();  
    if (empty($egresos)) {
          $datos = array(
             'code' => 404,
             'message' => 'No existen egresos'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
      }  
    return new View($egresos, Response::HTTP_OK);
  }     
}