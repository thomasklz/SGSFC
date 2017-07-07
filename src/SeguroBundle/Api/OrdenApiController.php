<?php
namespace SeguroBundle\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use SeguroBundle\Entity\Orden;
use SeguroBundle\Entity\Egreso;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class OrdenApiController extends FOSRestController {
  
   /**
     * You can register news orders
     * @ApiDoc(
     *     section = "Orders : Orders management",
     *     description="Register new order",
     *     statusCodes={
     *         201="Returned when is create successful",
     *         422="Returned when is unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *      {"name"="idbono", "dataType"="integer", "required"="true", "description"="to enter the id_bono"},
     *      {"name"="idafiliado", "dataType"="integer", "required"="true", "description"="to enter the id_socio"},
     *      {"name"="idusuario", "dataType"="integer", "required"="true", "description"="to enter the id_usuario"}
     *  }
     * )
    * @Rest\Post("/orden")
  */
  //Registrar nueva orden  
  public function nuevaAction(Request $request){
      $idbono=$request->get('idbono');
      $idafiliado=$request->get('idafiliado');
      $idusuario=$request->get('idusuario');
      if((empty($idbono)) || (empty($idafiliado)) || (empty($idusuario)))
          {
            $datos = array(
                      'code' => 422,
                      'message' => 'No se permiten valores nulos'
                      );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }
      $ordenExist = $this->getDoctrine()->getRepository('SeguroBundle:Orden')
                   ->findOneBy(array('idafiliado' => $idafiliado));
      if ($ordenExist) {
          $datos = array(
             'code' => 406,
             'message' => 'Error, el afiliado o socio ya tiene una orden generada'
            );
          return new View($datos, Response::HTTP_NOT_ACCEPTABLE);
         }
      $bono = $this->getDoctrine()->getRepository('SeguroBundle:Bono')
                   ->findOneBy(array('idbono' => $idbono));
      if (empty($bono)) {
          $datos = array(
             'code' => 404,
             'message' => 'Bono no encontrado'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
      $afiliado = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                   ->findOneBy(array('idafiliado' => $idafiliado, 'fallecido'=>1));
      if (empty($afiliado)) {
          $datos = array(
             'code' => 404,
             'message' => 'Afiliado no encontrado o aún no está en la lista de fallecidos'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
      $usuario = $this->getDoctrine()->getRepository('SeguroBundle:Usuario')
                   ->findOneBy(array('idusuario' => $idusuario));
      if (empty($usuario)) {
          $datos = array(
             'code' => 404,
             'message' => 'Usuario no encontrado'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }   
      $orden = new Orden();
      $orden->setIdbono($bono);
      $orden->setIdafiliado($afiliado);
      $orden->setIdusuario($usuario);
      $orden->setFechaorden(new \DateTime());
      $em = $this->getDoctrine()->getManager();
      $em->persist($orden);
      $em->flush();
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT o.idorden 
                                  FROM SeguroBundle:Orden o
                                  WHERE o.idorden=(select max(od.idorden)FROM SeguroBundle:Orden od)");
      $ordenId = $query->getResult(); 
      if (empty($ordenId)) {
          $datos = array(
             'code' => 404,
             'message' => 'No existe orden'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
      }else{
        return new View($ordenId, Response::HTTP_OK);
      }
    }
 
  /**
     * Returns a list of socios y/o afiliado that have orderns pending
     * @ApiDoc(
     *     section = "Orders : Orders management",
     *     description="Returns a list socios y/o afiliados for generate ordens",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
  *@Rest\Get("fallecidos/orden")
  */
  //Lista de fallecido que  tengan orden  pendiente  
  public function fallecidosordenAction(){  
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT a.idafiliado, a.nombre, a.apellido, a.cedula, a.fechanacimiento, a.fechaingreso
                                        , a.sexo, a.parentesco, a.fechafallecido, a.tipoafiliacion  
                                  FROM SeguroBundle:Afiliado a 
                                  WHERE a.fallecido=1 and  not exists (SELECT o FROM SeguroBundle:Orden o WHERE o.idafiliado= a.idafiliado)
                                  ORDER BY a.fechafallecido DESC ");
      $beneficiario = $query->getResult(); 
      if (empty($beneficiario)) {
          $datos = array(
                'code' => 404,
                'message' => 'No se encontró ningún socio o afiliado para generar orden'
          );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
      return $vista = $beneficiario;
  }
  
  /**
     * You can list orderns pending for register
     * @ApiDoc(
     *     section = "Orders : Orders management",
     *     description="Returns orderns pending for register",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
  *@Rest\Get("orden/pendiente")
  */  
  //Lista de ordenes pendiente para registrar(egresar) orden
  public function ordenRegistrarAction() {  
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery("SELECT o.idorden,a.idafiliado, o.fechaorden, CONCAT(a.nombre,' ', a.apellido) as Nombres,
                                        a.cedula , b.valor as valorBono,  sum(s.valor) as valorServicio
                                from SeguroBundle:Orden  o 
                                join o.idafiliado a
                                join o.idbono b
                                left join SeguroBundle:OredenServicio os where os.idorden=o.idorden
                                left join os.idservicio s
                                where a.fallecido=1 and not exists 
                                                  (select e.idegreso from SeguroBundle:Egreso e where e.idorden=o.idorden)  
                                group by a.cedula");
    $lisOrden = $query->getResult(); 
    if (empty($lisOrden)) {
          $datos = array(
                'code' => 404,
                'message' => 'No existen orden pendientes para registrar'
          );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
    return $vista = $lisOrden;
  } 
 
  /**
     * You can list the last orderns register in expenses
     * @ApiDoc(
     *     section = "Orders : Orders management",
     *     description="Returns last ordens register",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
  *@Rest\Get("ordenes/registradas")
  */  
   //Lista últimas ordenes registradas
  public function ordenUltimasAction(){  
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT o.idorden,a.idafiliado, e.valor, e.fechaegreso, CONCAT (a.nombre,' ', a.apellido) as                                Nombres, a.cedula 
                                from SeguroBundle:Orden  o 
                                join o.idafiliado a
                                join SeguroBundle:Egreso e where e.idorden=o.idorden");
    $ultimasOrden = $query->getResult(); 
    if (empty($ultimasOrden)) {
          $datos = array(
                'code' => 404,
                'message' => 'No existen ordenes registradas'
          );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
    return $vista = $ultimasOrden;
  } 
 
  /**
     * You can register the orders in expenses
     * @ApiDoc(
     *     section = "Orders : Orders management",
     *     description="Register orders in expenses",
     *     statusCodes={
     *         201="Returned when is create successful",
     *         400="Returned when is bad request",
     *         404="Returned when is not found",
     *         422="Returned when is unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *      {"name"="idorden", "dataType"="integer", "required"="true", "description"="to enter the id_orden"},
     *      {"name"="valor", "dataType"="double", "required"="true", "description"="to enter the value"},
     *      {"name"="idusuario", "dataType"="integer", "required"="true", "description"="to enter the id_usuario"}
     *  }
     * )
    * @Rest\Post("orden/registrar")
  */
  //Registrar orden en egresos  
  public function nuevoEgresoAction(Request $request){
      $idorden=$request->get('idorden');
      $valor=$request->get('valor');
      $idusuario=$request->get('idusuario');
      if((empty($idorden)) || (empty($valor)) || (empty($idusuario)))
          {
            $datos = array(
                      'code' => 422,
                      'message' => 'No se permiten valores nulos'
                      );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }
      $orden = $this->getDoctrine()->getRepository('SeguroBundle:Orden')
                   ->findOneBy(array('idorden' => $idorden));
      if (!$orden) {
          $datos = array(
             'code' => 404,
             'message' => 'Orden no existente'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
      $ordenExist = $this->getDoctrine()->getRepository('SeguroBundle:Egreso')
                   ->findOneBy(array('idorden' => $idorden));
      if ($ordenExist) {
          $datos = array(
             'code' => 400,
             'message' => 'Orden ya se encuentra egresada'
            );
          return new View($datos, Response::HTTP_BAD_REQUEST);
         }   
      $usuario = $this->getDoctrine()->getRepository('SeguroBundle:Usuario')
                   ->findOneBy(array('idusuario' => $idusuario));
      if (empty($usuario)) {
          $datos = array(
             'code' => 404,
             'message' => 'Usuario no existente'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }   
      $egreso = new Egreso();
      $egreso->setValor($valor);
      $egreso->setFechaegreso(new \DateTime());
      $egreso->setIdorden($orden);
      $egreso->setIdusuario($usuario);
      $em = $this->getDoctrine()->getManager();
      $em->persist($egreso);
      $em->flush();
      $datos = array(
             'code' => 201,
             'message' => 'Orden fue egresada correctamente'
            );
      return new View($datos, Response::HTTP_OK);
    } 
  
   /**
     * You can list ordens and services by idorden
     * @ApiDoc(
     *     section = "Orders : Orders management",
     *     description="Returns a list of ordens and services by idorden",
     *     statusCodes={
     *         200="Returned when is successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  requirements={
     *      {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_orden"}
     *  }
     * )
  *@Rest\Get("orden/{id}")
  */ 
  //ver ordenes de bono y servicios por idorden 
  public function ordenGeneralAction($id){  
    $orden = $this->getDoctrine()->getRepository('SeguroBundle:Orden')
                   ->findOneBy(array('idorden' => $id));
      if (!$orden) {
          $datos = array(
             'code' => 404,
             'message' => 'Orden no existente'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery("SELECT o.idorden, e.fechaegreso, e.valor as ValorEgreso, CONCAT(a.nombre,' ', a.apellido) as                                Nombres, b.bono, b.valor as ValorBono, s.servicio, s.valor as ValorServicio 
                                FROM SeguroBundle:Orden  o 
                                join o.idbono b
                                join o.idafiliado a
                                join SeguroBundle:Egreso e where e.idorden=o.idorden
                                left join SeguroBundle:OredenServicio os where os.idorden=o.idorden
                                left join os.idservicio s
                                where o.idorden= :idorden");
    $query->setParameters(array('idorden' => $id));
    $OrdenGeneral = $query->getResult(); 
    if (empty($OrdenGeneral)) {
          $datos = array(
                'code' => 404,
                'message' => 'No existen ordenes'
          );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
    return $vista = $OrdenGeneral;
  }           
}