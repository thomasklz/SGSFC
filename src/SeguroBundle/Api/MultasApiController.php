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
use SeguroBundle\Entity\Multa;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MultasApiController extends FOSRestController {
 /**
     * You can register news fines
     * @ApiDoc(
     *     section = "Fines : Fines management",
     *     description="Register new fine",
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
     *      {"name"="idafiliado", "dataType"="integer", "required"="true", "description"="to enter the id_socio"},
     *      {"name"="idreunion", "dataType"="integer", "required"="true", "description"="to enter the id_reunion"}
     *  }
     * )
  * @Rest\Post("/asistencia")
*/
 //Registrar inasistencia (Multa)
public function registrarasistenciaAction(Request $request){
  $idafiliado= $request->get("idafiliado");
  $idreunion = $request->get("idreunion");
  if((empty($idafiliado)) || (empty($idreunion))  )
          {
            $datos = array(
                    'code' => 422,
                    'message' => 'No se permiten valores nulos'
                  );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }      
  $afiliadoId = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                     ->findOneBy(array('idafiliado' => $idafiliado));
  if (empty($afiliadoId)) {
          $datos = array(
             'code' => 404,
             'message' => 'Socio no encontrado'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
  }
  $reunionId = $this->getDoctrine()->getRepository('SeguroBundle:Reunion')
                    ->findOneBy(array('idreunion' => $idreunion)); 
  if (empty($reunionId)) {
          $datos = array(
             'code' => 404,
             'message' => 'Reunión no encontrada'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
  }  
  $exitMulta = $this->getDoctrine()->getRepository('SeguroBundle:Multa')
                      ->findBy(array('idafiliado' => $idafiliado, 'idreunion' => $idreunion));
  if($exitMulta){
         $datos = array(
                    'code' => 400,
                    'message' => 'Ya tiene inasistencia a la reunión'
                  );
            return new View($datos, Response::HTTP_BAD_REQUEST); 
  }else{  
      $reunion= new Multa();    
      $reunion->setIdreunion($reunionId);
      $reunion->setIdafiliado($afiliadoId);
      $em = $this->getDoctrine()->getManager();
      $em->persist($reunion);
      $em->flush();
      $data=array(
                  'code' => 201,
                  'message' => 'Inasistencia registrada correctamente'
                  );
      return new View($data, Response::HTTP_CREATED);
    }  
  }
 
   /**
     * you can get a list to fines of socios by meeting
     * @ApiDoc(
     *     section = "Fines : Fines management",
     *     description="Returns id_socio with fines by meeting",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    requirements={
     *        {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_reunion"}
     *    }   
     * )
    * @Rest\Get("/reunion/inasistencia/{id}")
  */
   //Obtener lista multas por reunión
  public function reunioninasistenAction($id)
    {
      $reunion = $this->getDoctrine()->getRepository('SeguroBundle:Reunion')
                      ->findOneBy(array('idreunion' => $id)); 
      if(!$reunion){
          $datos = array(
                    'code' => 404,
                    'message' => 'Reunion no existente'
                  );
          return new View($datos, Response::HTTP_NOT_FOUND); 
        } 
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery('SELECT  a.idafiliado
                                  FROM SeguroBundle:Multa m JOIN m.idreunion r JOIN m.idafiliado a
                                  WHERE r.idreunion= :reunion');
      $query->setParameters(array('reunion' => $id));
      $reunion = $query->getResult();  
      if (empty($reunion)) {
          $datos = array(
             'code' => 404,
             'message' => 'No existen multas para esta reunión'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $reunion;
    }
  
  /**
     * you can get a list of fines to pay by socio
     * @ApiDoc(
     *     section = "Fines : Fines management",
     *     description="Returns fines to pay by socio",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    requirements={
     *        {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_socio"}
     *    }   
     * )
    * @Rest\Get("/reunion/multas/{id}")
  */
  //Obtener lista multas a pagar por afiliado 
  public function reunionmultasAction($id)
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
      $query = $em->createQuery('SELECT  a.idafiliado, m.idmulta, r.idreunion, r.temas, r.valormulta, r.fechareunion
                                  FROM SeguroBundle:Multa m JOIN m.idreunion r JOIN m.idafiliado a
                                  WHERE a.idafiliado= :afiliado and  not exists (SELECT i.idingreso FROM SeguroBundle:Ingreso i WHERE i.idmulta = m.idmulta)');
      $query->setParameters(array('afiliado' => $id));
      $multas = $query->getResult();  
      if (empty($multas)) {
          $datos = array(
             'code' => 404,
             'message' => 'No tiene multas adeudadas'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $multas;
    }  
  
   /**
     * you can delete the fines of the socio
     * @ApiDoc(
     *     section = "Fines : Fines management",
     *     description="Delete fines of the socio",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    requirements={
     *        {"name"="idmulta", "requirement"="true", "dataType"="integer", "description"="to enter the id_multa"}
     *    }   
     * )
    * @Rest\Delete("/reunion/multas/{idmulta}")
  */
   //Eliminar multas por afiliado
  public function deletemultaAction($idmulta)
    {
      $em = $this->getDoctrine()->getManager();
      $multas = $this->getDoctrine()->getRepository('SeguroBundle:Multa')->find($idmulta);
      if (empty($multas)) {
          $datos = array(
             'code' => 404,
             'message' => 'Multa no encontrada'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
      }
      $em->remove($multas);
      $em->flush();
      $data=array(
                  'code' => 200,
                  'message' => 'Multa eliminada correctamente'
                  );
      return new View($data, Response::HTTP_OK);
    }   
   
  /**
     * You can pay the fines of the socio
     * @ApiDoc(
     *     section = "Fines : Fines management",
     *     description="Pay fines of the socio",
     *     statusCodes={
     *         200="Returned when is successful",
     *         400="Returned when is bad request",
     *         404="Returned when is not found",
     *         422="Returned when is unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *      {"name"="valor", "dataType"="duoble", "required"="true", "description"="to enter the value to pay"},
     *      {"name"="idusuario", "dataType"="integer", "required"="true", "description"="to enter the id_usuario"},
     *      {"name"="idafiliado", "dataType"="integer", "required"="true", "description"="to enter the id_socio"},
     *      {"name"="idmulta", "dataType"="integer", "required"="true", "description"="to enter the id_multa"}
     *  }
     * )
   * @Rest\Post("/multa/pagar")
  */
  //Pago de multas 
  public function multapagarAction(Request $request)
    {
      $valor= $request->get("valor");
      $idUsuario=$request->get("idusuario");
      $idafiliado=$request->get("idafiliado");
      $idmulta=$request->get("idmulta");
      if((empty($valor)) || (empty($idUsuario)) || (empty($idafiliado)) || (empty($idmulta)) )
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
                    'message' => 'Usuario no encontrado'
                  );
          return new View($datos, Response::HTTP_NOT_FOUND); 
        }                  
      $tipoIngreso = $this->getDoctrine()->getRepository('SeguroBundle:Tipoingreso')
                      ->findOneBy(array('idtipoingreso' => 2));                 
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
      $multa = $this->getDoctrine()->getRepository('SeguroBundle:Multa')
                      ->findOneBy(array('idmulta' => $idmulta)); 
      if(!$multa)
        {
          $datos = array(
                    'code' => 404,
                    'message' => 'Multa no encontrada'
                  );
          return new View($datos, Response::HTTP_NOT_FOUND); 
        } 
      $multaCkeck = $this->getDoctrine()->getRepository('SeguroBundle:Ingreso')
                      ->findOneBy(array('idmulta' => $idmulta)); 
      if($multaCkeck)
        {
          $datos = array(
                    'code' => 400,
                    'message' => 'Multa ya había sido pagada'
                  );
          return new View($datos, Response::HTTP_BAD_REQUEST); 
        }     
      $mes= new Ingreso();
      $mes->setValor($valor);
      $mes->setFechaingreso(new \DateTime());
      $mes->setIdusuario($usuario);
      $mes->setIdtipoingreso($tipoIngreso);
      $mes->setIdafiliado($afiliado);
      $mes->setIdmulta($multa);
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