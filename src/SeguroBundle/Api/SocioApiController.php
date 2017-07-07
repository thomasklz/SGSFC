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

class SocioApiController extends FOSRestController{

  /**
     * Returns a list of socios
     * @ApiDoc(
     *     section = "Socios : Socios management",
     *     description="Returns a list of socios",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
    * @Rest\Get("/socios")
  */
  //Obtener lista de socios
  public function listAction()
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT a.idafiliado, a.nombre, a.apellido, a.cedula, a.fechanacimiento, a.fechaingreso
                                        , a.sexo, a.parentesco, (SELECT count(af.idafiliacionedependiente) FROM SeguroBundle:Afiliado af  WHERE af.idafiliacionedependiente =a.idafiliado) as Afiliados  
                                  FROM SeguroBundle:Afiliado a 
                                  WHERE a.tipoafiliacion='socio' and a.fallecido !=1 order by a.idafiliado Asc");
      $socios = $query->getResult(); 
      if (empty($socios)) {
            $datos = array(
                'code' => 404,
                'message' => 'No se encontró ningún socio'
            );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
      return $vista = $socios;
    }
 
  /**
     * Return a socio by id
     * @ApiDoc(
     *     section = "Socios : Socios management",
     *     description="Return a socio by id",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  requirements={
     *      {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_socio"}
     *  }
     * )
    * @Rest\Get("/socio/{id}")
  */
   //Obtener un  socio por código
  public function socioAction($id)
    {
      if (empty($id)) {
          $datos = array(
                        'code' => 422,
                        'message' => 'No se permiten valores nulos'
                    );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT a.idafiliado, a.nombre, a.apellido, a.cedula, a.fechanacimiento, a.fechaingreso
                                        , a.sexo, a.parentesco 
                                  FROM SeguroBundle:Afiliado a 
                                  WHERE a.tipoafiliacion='socio' and a.fallecido !=1 and a.idafiliado= :idafiliado");
      $query->setParameters(array('idafiliado' => $id));
      $socio = $query->getResult(); 
      if ($socio == null) {
          $datos = array(
                'code' => 404,
                'message' => 'Socio no encontrado'
          );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
      return $vista = $socio;
    }
  
  /**
     * You can search one socio by cédula 
     * @ApiDoc(
     *     section = "Socios : Socios management",
     *     description="Search socio by cédula",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *      {"name"="cedula", "dataType"="string", "required"="true", "description"="to enter cédula of the socio"}
     *  }
     * ) 
    * @Rest\Post("/socio/search/")
  */
  //buscar un  socio por cédula 
  public function searchAction(Request $request)
    {
      $cedula=$request->get("cedula");
      if (empty($cedula)) {
            $datos = array(
              'code' => 422,
              'message' => 'No se permiten valores nulos'
            );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY);
         }
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT a.idafiliado, a.nombre, a.apellido, a.cedula, a.fechanacimiento, a.fechaingreso
                                        , a.sexo, a.parentesco, (SELECT count(af.idafiliacionedependiente) FROM SeguroBundle:Afiliado af  WHERE af.idafiliacionedependiente =a.idafiliado)   as Afiliados 
                                  FROM SeguroBundle:Afiliado a 
                                  WHERE a.tipoafiliacion='Socio' and a.fallecido !=1 and a.cedula= :cedula");
      $query->setParameters(array(
          'cedula' => $cedula
      ));
      $socio = $query->getResult(); 
        if ($socio == null) {

            $datos = array(
                'code' => 404,
                'message' => 'Socio no encontrado'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $socio;
    }
 
    /**
     * You can search some socio or afiliado by cédula 
     * @ApiDoc(
     *     section = "Socios : Socios management",
     *     description="Search socio or afiliado by cédula",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *    {"name"="cedula","dataType"="string","required"="true","description"="to enter cédula of the socio or afiliado"}
     *  }
     * ) 
    * @Rest\Post("/socio/afiliado/search/")
  */
  //buscar un  socio o afiliado por cédula 
  public function searchSocioyAfiliadoAction(Request $request)
    {
      $cedula=$request->get("cedula");
      if (empty($cedula)) {
            $datos = array(
              'code' => 422,
              'message' => 'No se permiten valores nulos'
            );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY);
         }
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT a.idafiliado, a.nombre, a.apellido, a.cedula, a.fechanacimiento, a.fechaingreso
                                        , a.sexo, a.parentesco, a.tipoafiliacion
                                  FROM SeguroBundle:Afiliado a 
                                  WHERE a.fallecido !=1 and a.cedula= :cedula");
      $query->setParameters(array(
          'cedula' => $cedula
      ));
      $socio = $query->getResult(); 
        if ($socio == null) {

            $datos = array(
                'code' => 404,
                'message' => 'Socio o afiliado no encontrado'
            );
          return new View($datos, Response::HTTP_NOT_FOUND);
         }
        return $vista = $socio;
    }  
  
  /**
     * You can register a new socio 
     * @ApiDoc(
     *     section = "Socios : Socios management",
     *     description="Register new socio",
     *     statusCodes={
     *         201="Returned when is create successful",
     *         400="Returned when is bad request",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *    {"name"="nombre","dataType"="string","required"="true","description"="to enter name of the socio"},
     *    {"name"="apellido","dataType"="string","required"="true","description"="to enter lastname of the socio"},
     *    {"name"="cedula","dataType"="string","required"="true","description"="to enter cédula of the socio"},
     *    {"name"="fechanacimiento","dataType"="date","required"="true","description"="to enter birth date of the socio"},
     *    {"name"="sexo","dataType"="string","required"="true","description"="to enter sexo of the socio"},
     *    {"name"="parentesco","dataType"="string","required"="true","description"="to enter the parentesco"},
     *    {"name"="tipoafiliacion","dataType"="string","required"="true","description"="to enter type membership"}
     *  }
     * ) 
    * @Rest\Post("/socio/")
  */
  //Ingresar nuevo socio
  public function createAction(Request $request)
    {
      $nombres = $request->get('nombre');
      $apellidos = $request->get('apellido');
      $cedula = $request->get('cedula');
      $fechanacimiento = $request->get('fechanacimiento');
      $sexo = $request->get('sexo');
      $parentesco = $request->get('parentesco');
      $tipoafiliacion = $request->get('tipoafiliacion');
      if((empty($nombres)) || (empty($apellidos)) || (empty($cedula)) || (empty($fechanacimiento)) || (empty($sexo)) || (empty($parentesco)) || (empty($tipoafiliacion)))
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
                    'message' => 'Ya se encuentra registrado'
                );
          return new View($data, Response::HTTP_BAD_REQUEST); 
         } 
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
        $em = $this->getDoctrine()->getManager();
        $em->persist($afiliado);
        $em->flush();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT max(a.idafiliado) as idafiliado 
                                    FROM SeguroBundle:Afiliado a");
        $afiliadoId = $query->getResult(); 
        return new View($afiliadoId, Response::HTTP_CREATED);
        
    }
 
    /**
     * You can update the data of the socio 
     * @ApiDoc(
     *     section = "Socios : Socios management",
     *     description="Update a socio",
     *     statusCodes={
     *         200="Returned when is successful",
     *         400="Returned when is bad request",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *     requirements={
     *         {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_socio"}
     *     },
     *    parameters={
     *       {"name"="nombre","dataType"="string","required"="true","description"="to enter name of the socio"},
     *       {"name"="apellido","dataType"="string","required"="true","description"="to enter lastname of the socio"},
     *       {"name"="cedula","dataType"="string","required"="true","description"="to enter cédula of the socio"},
     *       {"name"="fechanacimiento","dataType"="date","required"="true","description"="to enter birth date of the socio"},
     *       {"name"="sexo","dataType"="string","required"="true","description"="to enter sexo of the socio"},
     *       {"name"="parentesco","dataType"="string","required"="true","description"="to enter the parentesco"}
     *    }
     * ) 
    * @Rest\Put("/socio/{id}")
  */
  //Actualizar datos del socio  
  public function apdateAction(Request $request, $id)
    {
      $nombres = $request->get('nombre');
      $apellidos = $request->get('apellido');
      $cedula = $request->get('cedula');
      $fechanacimiento = $request->get('fechanacimiento');
      $sexo = $request->get('sexo');
      $parentesco = $request->get('parentesco');
      if((empty($nombres)) || (empty($apellidos)) || (empty($cedula)) || (empty($fechanacimiento)) || (empty($sexo)) || (empty($parentesco)))
          {
            $datos = array(
                      'code' => 422,
                      'message' => 'No se permiten valores nulos'
                     );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }
      $em = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado');
      $resultSocio = $em->find($id);
      if(empty($resultSocio)){
              $data=array(
                    'code' => 404,
                    'message' => 'Socio no encontrado'
                    );
            return new View($data, Response::HTTP_NOT_FOUND);   
      } 
      $query = $em->createQueryBuilder('s')
              ->where('s.cedula = :cedula and s.idafiliado != :id')
              ->setParameters(array( 'cedula' => $cedula,
                                      'id' => $id))
              ->getQuery();
      $socioCedula = $query->getResult();
      if($socioCedula){
          $data=array(
                    'code' => 400,
                    'message' => 'Ya se encuentra registrado'
                  );
        return new View($data, Response::HTTP_BAD_REQUEST);        
      }      
      $resultSocio->setNombre($nombres);
      $resultSocio->setApellido($apellidos);
      $resultSocio->setCedula($cedula);
      $resultSocio->setFechanacimiento(new \DateTime($fechanacimiento));
      $resultSocio->setSexo($sexo);
      $resultSocio->setParentesco($parentesco);
      $em = $this->getDoctrine()->getManager();
      $em->persist($resultSocio);
      $em->flush();
      $data=array(
                'code' => 200,
                'message' => 'Datos actualizados correctamente'
            );
      return new View($data, Response::HTTP_OK);
    }
 
   /**
     * You can get the last pay of the socio 
     * @ApiDoc(
     *     section = "Socios : Socios management",
     *     description="Return last pay of the socio",
     *     statusCodes={
     *         200="Returned when is successful",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *     requirements={
     *         {"name"="id", "requirement"="true", "dataType"="integer", "description"="to enter the id_socio"}
     *     }
     * ) 
    * @Rest\Get("/usuario/pagos/{id}")
  */
  //Obtener un  ulitmos 5 pagos por id socio
  public function sociotoppagosAction($id)
    {
      if (empty($id)) {
            $datos = array(
              'code' => 422,
              'message' => 'No se permiten valores nulos'
            );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY);
         }
      $em = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado');
      $afiliado = $em->findOneBy(array('idafiliado' => $id));
      if(empty($afiliado)){
              $data=array(
                    'code' => 404,
                    'message' => 'Socio no encontrado'
                    );
            return new View($data, Response::HTTP_NOT_FOUND);   
      }
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT i.fechaingreso, i.valor, ti.nombreingreso 
                                  FROM SeguroBundle:Ingreso i 
                                       join i.idafiliado a  
                                       join i.idtipoingreso ti
                                  WHERE a.idafiliado= :afiliado order by i.idingreso desc");
      $query->setParameters(array('afiliado' => $id));
      $socio = $query->setMaxResults(5)->getResult();
      if ($socio == null) {
          $datos = array(
                'code' => 404,
                'message' => 'No se encontraron pagos'
          );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
      return $vista = $socio;
    }
 
   /**
     * You can change of socio to afiliado or afiliado to socio 
     * @ApiDoc(
     *     section = "Socios : Socios management",
     *     description="Change of socio to afiliado or afiliado to socio",
     *     statusCodes={
     *         200="Returned when is successful",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *    requirements={
     *       {"name"="idsocio","dataType"="integer","required"="true","description"="to enter the id_socio"},
     *       {"name"="idafiliado","dataType"="integer","required"="true","description"="to enter the  id_afiliado"}
     *    }
     * )
     * @Rest\Put("/socio/cambio/{idsocio}/{idafiliado}")
  */
  //Cambiar socio a afiliado y viceversa
  public function cambioDEDAction(Request $request,  $idsocio, $idafiliado )
    {

      if((empty($idsocio)) || (empty($idafiliado)))
          {
            $datos = array(
                      'code' => 422,
                      'message' => 'No se permiten valores nulosss'
                     );
            return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
          }
      $em = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado');
      $resultSocio = $em->find($idsocio);
      $em = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado');
      $resultAfiliado = $em->find($idafiliado);
      if(empty($resultSocio)){
              $data=array(
                    'code' => 404,
                    'message' => 'Socio no encontrado'
                    );
            return new View($data, Response::HTTP_NOT_FOUND);   
      }
      if(empty($resultAfiliado)){
              $data=array(
                    'code' => 404,
                    'message' => 'Afiliado no encontrado'
                    );
            return new View($data, Response::HTTP_NOT_FOUND);   
      }
      if (($resultSocio) && ($resultAfiliado)) {

         $nombres = $resultSocio->getNombre();
         $apellidos = $resultSocio->getApellido();
         $cedula = $resultSocio->getCedula();
         $fechanacimiento = $resultSocio->getFechanacimiento();
         $sexo = $resultSocio->getSexo();
         $parentesco = $resultSocio->getParentesco();

         $resultSocio->setNombre($resultAfiliado->getNombre());
         $resultSocio->setApellido($resultAfiliado->getApellido());
         $resultSocio->setCedula($resultAfiliado->getCedula());
         $resultSocio->setFechanacimiento($resultAfiliado->getFechanacimiento());
         $resultSocio->setSexo($resultAfiliado->getSexo());
         $resultSocio->setParentesco($resultAfiliado->getParentesco());

         $resultAfiliado->setNombre($nombres);
         $resultAfiliado->setApellido($apellidos);
         $resultAfiliado->setCedula($cedula);
         $resultAfiliado->setFechanacimiento($fechanacimiento);
         $resultAfiliado->setSexo($sexo);
         $resultAfiliado->setParentesco($parentesco);
         
         $em = $this->getDoctrine()->getManager();
         $em->persist($resultAfiliado);
         $em->flush();

         $em = $this->getDoctrine()->getManager();
         $em->persist($resultSocio);
         $em->flush();

         $data=array(
                'code' => 200,
                'message' => 'Datos actualizados correctamente'
            );
         return new View($data, Response::HTTP_OK);
      }
    }      
 }

 