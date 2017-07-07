<?php
namespace SeguroBundle\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use SeguroBundle\Entity\Afiliado;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class BeneficiarioApiController extends FOSRestController {
	
  /**
     * you can to get the list of the afiliados of the socio
     * @ApiDoc(
     *     section = "Afiliados : Afiliados management",
     *     description="Return list of afiliados of the socio",
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
    * @Rest\Get("/socio/{id}/afiliados")
  */
  //Obtener todos los afiliados del socios
  public function socioafiliadosAction($id)
    {
      $afiliado = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                      ->findOneBy(array('idafiliado' => $id, 'fallecido' => 0 )); 
      if(!$afiliado)
        {
          $datos = array(
                    'code' => 404,
                    'message' => 'Socio no encontrado'
                  );
          return new View($datos, Response::HTTP_NOT_FOUND); 
        }  
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT a.idafiliado, a.nombre, a.apellido, a.cedula, a.fechanacimiento, a.fechaingreso
                                        , a.sexo, a.parentesco, a.fallecido  
                                  FROM SeguroBundle:Afiliado a 
                                  WHERE a.idafiliacionedependiente=".$id);
      $beneficiario = $query->getResult(); 
      if (empty($beneficiario)) {
          $datos = array(
                'code' => 404,
                'message' => 'No se encontró ningún afiliado'
          );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
      return $vista = $beneficiario;
    }
  
  /**
     * You can register a new afiliado of the socio 
     * @ApiDoc(
     *     section = "Afiliados : Afiliados management",
     *     description="Register new afiliado of the socio",
     *     statusCodes={
     *         201="Returned when is create successful",
     *         400="Returned when is bad request",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    parameters={
     *       {"name"="nombre","dataType"="string","required"="true","description"="to enter name of the socio"},
     *       {"name"="apellido","dataType"="string","required"="true","description"="to enter lastname of the socio"},
     *       {"name"="cedula","dataType"="integer","required"="true","description"="to enter cédula of the socio"},
     *       {"name"="fechanacimiento","dataType"="date","required"="true","description"="to enter birth date of the socio"},
     *       {"name"="sexo","dataType"="string","required"="true","description"="to enter sexo of the socio"},
     *       {"name"="parentesco","dataType"="string","required"="true","description"="to enter the parentesco"},
     *       {"name"="tipoafiliacion","dataType"="string","required"="true","description"="to enter type membership"},
     *       {"name"="idafiliacionedependiente","dataType"="integer","required"="true","description"="to enter the id_socio"}
     *     }
     * ) 
  * @Rest\Post("/beneficiario/")
  */
  //Insertar nuevo beneficiario
  public function createbeneficiarioAction(Request $request)
    {
      $nombres = $request->get('nombre');
      $apellidos = $request->get('apellido');
      $cedula = $request->get('cedula');
      $fechanacimiento = $request->get('fechanacimiento');
      $sexo = $request->get('sexo');
      $parentesco = $request->get('parentesco');
      $tipoafiliacion = $request->get('tipoafiliacion');
      $idafiliacionedependiente = $request->get('idafiliacionedependiente');
      if((empty($nombres)) || (empty($apellidos)) || (empty($cedula)) || (empty($fechanacimiento)) || (empty($sexo)) || (empty($parentesco)) || (empty($tipoafiliacion)) || (empty($idafiliacionedependiente)))
        {
          $datos = array(
                        'code' => 422,
                        'message' => 'No se permiten valores nulos'
                );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
        }
      $socio = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                    ->findOneBy(array('cedula' => $cedula));
      if($socio){
          $data=array(
                    'code' => 400,
                    'message' => 'Afiliado ya se encuentra registrado'
                );
          return new View($data, Response::HTTP_BAD_REQUEST); 
        } 
        else
        {
          $Idsocio = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                          ->find(array('idafiliado' => $idafiliacionedependiente));
          $afiliado = new Afiliado;   
          $afiliado->setNombre($nombres);
          $afiliado->setApellido($apellidos);
          $afiliado->setCedula($cedula);
          $afiliado->setFechanacimiento(new \DateTime($fechanacimiento));
          $afiliado->setFechaingreso(new \DateTime());
          $afiliado->setFallecido(0);
          $afiliado->setSexo($sexo);
          $afiliado->setParentesco($parentesco);
          $afiliado->setTipoafiliacion($tipoafiliacion);
          $afiliado->setIdafiliacionedependiente($Idsocio);
          $em = $this->getDoctrine()->getManager();
          $em->persist($afiliado);
          $em->flush();
          $data=array(
                    'code' => 201,
                    'message' => 'Afiliado registrado correctamente'
                );
          return new View($data, Response::HTTP_CREATED);
        }
    }
   
  /**
     * you can to list the socios and afiliados deads
     * @ApiDoc(
     *     section = "Afiliados : Afiliados management",
     *     description="Returns socios and afiliados deads",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
  *@Rest\Get("socios/afiliados/fallecidos")
  */ 
  //Lista de fallecido  
  public function fallecidosAction(){  
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT a.idafiliado, a.nombre, a.apellido, a.cedula, a.fechanacimiento, a.fechaingreso
                                        , a.sexo, a.parentesco, a.fechafallecido, a.tipoafiliacion  
                                  FROM SeguroBundle:Afiliado a 
                                  WHERE a.fallecido=1 ORDER BY a.fechafallecido DESC ");
      $beneficiario = $query->getResult(); 
      if (empty($beneficiario)) {
          $datos = array(
                'code' => 404,
                'message' => 'No se encontró ningún socio o afiliado fallecido'
          );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
      return $vista = $beneficiario;
  } 
 
   /**
     * you can update the state of socio or afiliado to dead
     * @ApiDoc(
     *     section = "Afiliados : Afiliados management",
     *     description="Update tate of socio or afiliado to dead",
     *     statusCodes={
     *         200="Returned when is successful",
     *         404="Returned when is not found",
     *         422="Returned when is unprocessable entity",
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    requirements={
     *        {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_socio"}
     *    },
    *    parameters={
     *       {"name"="estado","dataType"="boolean","required"="true","description"="to enter the state"},
     *       {"name"="fechafallecido","dataType"="date","required"="true","description"="to enter the date of dead"}
     *     }   
     * )
    * @Rest\Put("/socios/afiliados/{id}/fallecidos")
  */
   //Actualizar estado fallecido 
  public function apdateFallecidoAction(Request $request, $id)
    {
      $fallecido = $request->get('estado');
      $fechafallecido = $request->get('fechafallecido');
      $resultSocio = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                          ->findOneBy(array('idafiliado' => $id));
      if(empty($resultSocio))
      {
        $data=array(
                    'code' => 404,
                    'message' => 'Socio o afiliado no encontrado'
                    );
        return new View($data, Response::HTTP_NOT_ACCEPTABLE);   
      } 
      if((($fallecido == 0) || ($fallecido == 1)) && (!empty($fechafallecido)))
        {
          if ($fallecido == 0){
              $resultSocio->setFechafallecido(null);
          }
          if ($fallecido == 1){
              $resultSocio->setFechafallecido(new \DateTime($fechafallecido));
          }
          $resultSocio->setFallecido($fallecido);
          $em = $this->getDoctrine()->getManager();
          $em->persist($resultSocio);
          $em->flush();
          $data=array(
                       'code' => 200,
                       'message' => 'Estado actualizado correctamente'
                      );
          return new View($data, Response::HTTP_OK);
        }
      else
        {
          $datos = array(
                          'code' => 422,
                          'message' => 'Existen valores nulos o no permitidos'
                        );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
        }
    }
  
  /**
     * you can to get one afiliado by id_afiliado
     * @ApiDoc(
     *     section = "Afiliados : Afiliados management",
     *     description="Returns one afiliado by id_afiliado",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    requirements={
     *        {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_afiliado"}
     *    }
     * )
    * @Rest\Get("beneficiario/{id}")
  */
  //Obtener un  beneficiario por id
  public function listBeneficiarioAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT a.idafiliado, a.nombre, a.apellido, a.cedula, a.fechanacimiento, a.fechaingreso
                                        , a.sexo, a.parentesco 
                                  FROM SeguroBundle:Afiliado a 
                                  WHERE a.tipoafiliacion='afiliado' and a.fallecido !=1 and a.idafiliado= :afiliado");
      $query->setParameters(array('afiliado' => $id));
      $beneficiario = $query->getResult(); 
      if ($beneficiario == null) {
          $datos = array(
                'code' => 404,
                'message' => 'Beneficiario no encontrado'
          );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
      return $vista = $beneficiario;
    }     
}