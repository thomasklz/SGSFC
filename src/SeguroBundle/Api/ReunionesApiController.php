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
use SeguroBundle\Entity\Reunion;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class ReunionesApiController extends FOSRestController {
  
   /**
     * You can register news meeting
     * @ApiDoc(
     *     section = "Meeting : Meeting management",
     *     description="Register new meet",
     *     statusCodes={
     *         201="Returned when is create successful",
     *         422="Returned when is unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *      {"name"="tema", "dataType"="string", "required"="true", "description"="to enter subject of the meet"},
     *      {"name"="descripcion", "dataType"="string", "required"="true", "description"="to enter descripcion of the meet"},
     *      {"name"="fechareunion", "dataType"="datetime", "required"="true", "description"="to enter date of the meet"},
     *      {"name"="valorreunion", "dataType"="double", "required"="true", "description"="to enter value of the meet"},
     *      {"name"="valormulta", "dataType"="double", "required"="true", "description"="to enter value of the fine"},
     *  }
     * )
   * @Rest\Post("/reunion")
  */
  //Ingresar reuniones 
  public function newreunionAction(Request $request)
    {
      $tema= $request->get("tema");
      $descripcion=$request->get("descripcion");
      $fechareunion=$request->get("fechareunion");
      $valorreunion=$request->get("valorreunion");
      $valormulta=$request->get("valormulta");
      if((empty($tema)) || (empty($descripcion)) || (empty($fechareunion)) || (empty($valorreunion)) || (empty($valormulta)) )
          {
            $datos = array(
                    'code' => 422,
                    'message' => 'No se permiten valores nulos'
                  );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }     
      $reunion= new Reunion();    
      $reunion->setTemas($tema);
      $reunion->setDescripcion($descripcion);
      $reunion->setFechareunion(\DateTime::createFromFormat ('Y-m-d H:i:s', $fechareunion));
      $reunion->setValorreunion($valorreunion);
      $reunion->setValormulta($valormulta);
      $em = $this->getDoctrine()->getManager();
      $em->persist($reunion);
      $em->flush();
      $data=array(
                  'code' => 201,
                  'message' => 'Reunión ingresada correctamente'
                  );
      return new View($data, Response::HTTP_CREATED);
    }
 
   /**
     * You can get the meeting of today
     * @ApiDoc(
     *     section = "Meeting : Meeting management",
     *     description="Return meet of today",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
    * @Rest\Get("/reunion")
  */
  //Obtener reunión actual a la fecha
  public function reunionactualAction()
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT r.idreunion, r.temas, r.descripcion, r.fechareunion, r.valorreunion, r.valormulta  
                                  FROM SeguroBundle:Reunion  r  
                                  WHERE date(r.fechareunion) = CURRENT_DATE() order by time(r.fechareunion) Asc");
      $reunion = $query->getResult(); 
      if (empty($reunion)) {
          $datos = array(
             'code' => 404,
             'message' => 'No existe reunión para hoy'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $reunion;
    }
  
  /**
     * You can get a list of meeting
     * @ApiDoc(
     *     section = "Meeting : Meeting management",
     *     description="Returns a list of meeting",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
    * @Rest\Get("/reunion/listado")
  */
   //Obtener listado de todas las reuniones
  public function reunionaListadoAction()
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT r.idreunion, r.temas, r.descripcion, r.fechareunion, r.valorreunion, r.valormulta  
                                  FROM SeguroBundle:Reunion  r ");
      $reunion = $query->getResult(); 
      if (empty($reunion)) {
          $datos = array(
             'code' => 404,
             'message' => 'No existe reuniones'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $reunion;
    }
  
  /**
     * You can get the list of meeting to pay by Socio
     * @ApiDoc(
     *     section = "Meeting : Meeting management",
     *     description="Returns meeting to pay by Socio",
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
    * @Rest\Get("/reunion/adeuda/{id}")
  */
  //Obtener lista reuniones a pagar por afiliado 
  public function reunionAdeudadasAction($id)
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
      $query = $em->createQuery('SELECT  r.idreunion, r.temas, r.descripcion, r.fechareunion, r.valorreunion, 
                                         r.valormulta 
                                  FROM SeguroBundle:Reunion r 
                                  WHERE not exists (SELECT i.idingreso 
                                                    FROM SeguroBundle:Ingreso i JOIN i.idafiliado a
                                                    WHERE i.idreunion = r.idreunion and a.idafiliado= :afiliado )');
      $query->setParameters(array('afiliado' => $id));
      $multas = $query->getResult();  
      if (empty($multas)) {
          $datos = array(
             'code' => 404,
             'message' => 'No tiene reuniones adeudadas'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $multas;
    } 
  
  /**
     * You can pay to meeting
     * @ApiDoc(
     *     section = "Meeting : Meeting management",
     *     description="Pay to meet",
     *     statusCodes={
     *         200="Returned when is successful",
     *         400="Returned when is bad request",
     *         404="Returned when is not found",
     *         422="Returned when is unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *     parameters={
     *        {"name"="valor", "dataType"="double", "required"="true", "description"="to enter value to pay"},
     *        {"name"="idusuario", "dataType"="integer", "required"="true", "description"="to enter the id_usuario"},
     *        {"name"="idafiliado", "dataType"="integer", "required"="true", "description"="to enter the id_socio"},
     *        {"name"="idreunion", "dataType"="integer", "required"="true", "description"="to enter the id_reunion"}
     *     }
     *  )
   * @Rest\Post("/reunion/pagar")
  */
   //Pago de Reunion 
  public function reunionpagarAction(Request $request)
    {
      $valor= $request->get("valor");
      $idUsuario=$request->get("idusuario");
      $idafiliado=$request->get("idafiliado");
      $idreunion=$request->get("idreunion");
      if((empty($valor)) || (empty($idUsuario)) || (empty($idafiliado)) || (empty($idreunion)) )
          {
            $datos = array(
                    'code' => 422,
                    'message' => 'No se permiten valores nulos'
                  );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }                      
      $usuario = $this->getDoctrine()->getRepository('SeguroBundle:Usuario')
                      ->findOneBy(array('idusuario' => $idUsuario)); 
      if(!$usuario)
        {
          $datos = array(
                    'code' => 404,
                    'message' => 'Usuario no existente'
                  );
          return new View($datos, Response::HTTP_NOT_FOUND); 
        } 
      $tipoIngreso = $this->getDoctrine()->getRepository('SeguroBundle:Tipoingreso')
                      ->findOneBy(array('idtipoingreso' => 3));  
      $afiliado = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                      ->findOneBy(array('idafiliado' => $idafiliado));
      if(!$afiliado)
        {
          $datos = array(
                    'code' => 404,
                    'message' => 'Socio no existente'
                  );
          return new View($datos, Response::HTTP_NOT_FOUND); 
        }   
      $reunion = $this->getDoctrine()->getRepository('SeguroBundle:Reunion')
                      ->findOneBy(array('idreunion' => $idreunion));
      if(!$reunion)
        {
          $datos = array(
                    'code' => 404,
                    'message' => 'Reunion no existente'
                  );
          return new View($datos, Response::HTTP_NOT_FOUND); 
        }  
      $reunionCkeck = $this->getDoctrine()->getRepository('SeguroBundle:Ingreso')
                      ->findOneBy(array('idreunion' => $idreunion)); 
      if($reunionCkeck)
        {
          $datos = array(
                    'code' => 400,
                    'message' => 'Reunión ya había sido pagada'
                  );
          return new View($datos, Response::HTTP_NOT_ACCEPTABLE); 
        }    
      $mes= new Ingreso();
      $mes->setValor($valor);
      $mes->setFechaingreso(new \DateTime());
      $mes->setIdusuario($usuario);
      $mes->setIdtipoingreso($tipoIngreso);
      $mes->setIdafiliado($afiliado);
      $mes->setIdreunion($reunion);
      $em = $this->getDoctrine()->getManager();
      $em->persist($mes);
      $em->flush();
      $data=array(
                  'code' => 200,
                  'message' => 'Pago realizado correctamente'
                  );
      return new View($data, Response::HTTP_OK);
    }  
     
}