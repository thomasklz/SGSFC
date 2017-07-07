<?php

namespace SeguroBundle\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use SeguroBundle\Entity\Usuario;
use SeguroBundle\Entity\Tipousuario;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserApiController extends FOSRestController
{
   /**
     * Returns a list of users
     * @ApiDoc(
     *     section="Users : Users management",
     *     description="Returns a list of users",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the user is not found"
     *     },
     *     tags={
     *         "stable" = "green",
     *     }
     * )
    * @Rest\Get("/usuarios")
  */
  public function listAction()
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery('SELECT u.idusuario, u.usuario, u.fechacreacion, u.estado, tp.tipo 
                                  FROM SeguroBundle:Usuario u JOIN u.idtipousuario  tp
                                  WHERE u.estado=1');
      $usuarios = $query->getResult(); 
      if($usuarios === null) {
          $datos = array(
                      'code' => 404,
                      'message' => 'Usuario no encontrado'
                    );
          return new View($datos, Response::HTTP_NOT_FOUND);
      }
      return $vista = array('Usuarios' => $usuarios );
    }
  
  /**
     * Return an user by id
     * @ApiDoc(
     *     section = "Users : Users management",
     *     description="Return an user by id",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the user is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  requirements={
     *      {"name"="id", "dataType"="integer","requirement"="true","description"="id_user"}
     *  }
     * )
    * @Rest\Get("/usuario/{id}")
  */
  //Obtener un  usuario
  public function userAction($id)
    {
      if (empty($id)) {
          $datos = array(
                        'code' => 422,
                        'message' => 'No se permiten valores nulos'
                    );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery('SELECT u.idusuario, u.usuario, u.fechacreacion, u.estado, tp.tipo 
                                  FROM SeguroBundle:Usuario u JOIN u.idtipousuario  tp
                                  WHERE u.idusuario='.$id);
      $result = $query->getResult(); 
      if ($result == null) {
          $datos = array(
                        'code' => 404,
                        'message' => 'Usuario no encontrado'
                    );
          return new View($datos, Response::HTTP_NOT_FOUND);
      }
      return $vista = $result;
    }
    /**
     * Login of user
     * @ApiDoc(
     *     section = "Users : Users management",
     *     description="Login of user",
     *     statusCodes={
     *         200="Returned when successful",
     *         406="Returned when not acceptable",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *      {"name"="user", "dataType"="string", "required"="true", "description"="username the of user"},
     *      {"name"="password", "dataType"="string", "required"="true", "description"="password the of user"}
     *  }
     * )
    * @Rest\Post("/logearse")
    //Logearse en el sistemas
  */
  public function loginAction(Request $request)
    {
      $user = 'Thomas';
      $password = '123';
      if ((empty($user)) || (empty($password))) {
          $datos = array(
                        'code' => 422,
                        'message' => 'No se permiten valores nulos'
                    );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
      $result = $this->getDoctrine()->getRepository('SeguroBundle:Usuario')->findOneBy(array('usuario' =>  $user, 'estado'=> 1));
      if ($result === null) {
          $datos = array(
                        'code' => 406,
                        'message' => 'Login incorrecto'
                    );
          return new View($datos, Response::HTTP_NOT_ACCEPTABLE);
        }
        else
        {
          $salt = $result->getSalt();
          $hash = hash("sha256", $password . $salt, false);
          if ($hash == $result->getPassword()){
              return $vista = array('Usuario' => $result);
            }
        }
    }
 
  /**
     * You can create news users
     * @ApiDoc(
     *     section = "Users : Users management",
     *     description="Create new user",
     *     statusCodes={
     *         201="Returned when successful",
     *         400="Returned when is bad request",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  parameters={
     *      {"name"="usuario", "dataType"="string", "required"="true", "description"="new username"},
     *      {"name"="idafiliado", "dataType"="integer", "required"="true", "description"="id_afiliado the of socio"},
     *      {"name"="password", "dataType"="string", "required"="true", "description"="new password"},
     *      {"name"="idtipousuario", "dataType"="integer", "required"="true", "description"="to enter id_tipousuario"}
     *  }
     * )
    * @Rest\Post("/usuario/")
  */
   //Ingresar un Usuario
  public function createAction(Request $request)
    {
      $user = $request->get('usuario');
      $idafiliado = $request->get('idafiliado');
      $pass = $request->get('password');
      $tipo = $request->get('idtipousuario');
      if((empty($user)) || (empty($pass)) || (empty($tipo))|| (empty($idafiliado)))
        {
          $datos = array(
                        'code' => 422,
                        'message' => 'No se permiten valores nulos '
                    );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
        }
      $resultUser = $this->getDoctrine()->getRepository('SeguroBundle:Usuario')->findOneBy(array('usuario' => $user));
      if($resultUser){
            $data=array(
                       'code' => 400,
                       'message' => 'Usuario existente'
                  );
          return new View($data, Response::HTTP_BAD_REQUEST); 
        } 
      $UserSocio = $this->getDoctrine()->getRepository('SeguroBundle:Usuario')
                     ->findOneBy(array('idafiliado' => $idafiliado)); 
      if($UserSocio){
            $data=array(
                       'code' => 400,
                       'message' => 'Socio ya tiene una cuenta de usuario'
                  );
          return new View($data, Response::HTTP_BAD_REQUEST); 
        }                
      $idTipo = $this->getDoctrine()->getRepository('SeguroBundle:Tipousuario')
                     ->findOneBy(array('idtipousuario' => $tipo));
      $afiliado = $this->getDoctrine()->getRepository('SeguroBundle:Afiliado')
                        ->findOneBy(array('idafiliado' => $idafiliado, 'tipoafiliacion' =>'Socio'));
      if(!$afiliado){
            $data=array(
                       'code' => 404,
                       'message' => 'Socio no encontrado'
                  );
          return new View($data, Response::HTTP_NOT_FOUND); 
        }  
      $usuario = new Usuario;   
      $usuario->setUsuario($user);
      $salt = microtime();
      $hash = hash("sha256", $pass . $salt, false);
      $usuario->setPassword($hash);
      $usuario->setSalt($salt);
      $usuario->setEstado(1);
      $usuario->setFechacreacion(new \DateTime());
      $usuario->setFoto("img/profile-pic.jpg");
      $usuario->setIdtipousuario($idTipo);
      $usuario->setIdafiliado($afiliado);
      $em = $this->getDoctrine()->getManager();
      $em->persist($usuario);
      $em->flush();
      $data=array(
                   'code' => 201,
                   'message' => 'Usuario registrado correctamente'
               );
      return new View($data, Response::HTTP_CREATED);
    }
 
  /**
     * You can update an user
     * @ApiDoc(
     *     section = "Users : Users management",
     *     description="Update an user",
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when is bad request",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  requirements={
     *      {"name"="id", "requirement"="true", "dataType"="integer",  "description"="to enter id of the user"}
     *  },
     *  parameters={
     *      {"name"="usuario", "dataType"="string", "required"="true", "description"="to enter username"},
     *      {"name"="idTipouser", "dataType"="integer", "required"="true", "description"="to enter id_tipousuario"},
     *      {"name"="password", "dataType"="string", "required"="true", "description"="to enter password"},
     *      {"name"="estado", "dataType"="boolean", "required"="true", "description"="to enter estado"}
     *  }
     * )
    * @Rest\Put("/usuario/{id}")
  */
   //Actualizar usuario
  public function apdateAction(Request $request, $id)
    {
      $user = $request->get('usuario');
      $idTipouser = $request->get('idTipouser');
      $pass = $request->get('password');
      $esta = $request->get('estado');
      if((empty($user)) || (empty($pass))|| ($esta==null) || (empty($idTipouser)))
        {
          $datos = array(
                        'code' => 422,
                        'message' => 'No se permiten valores nulos'
                    );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
        }
      $em = $this->getDoctrine()->getRepository('SeguroBundle:Usuario');
      $resultUser = $em->find($id);
      if(empty($resultUser)){
            $data=array(
                       'code' => 404,
                       'message' => 'Usuario no encontrado'
                  );
          return new View($data, Response::HTTP_NOT_FOUND);   
        }
      $Tipo = $this->getDoctrine()->getRepository('SeguroBundle:Tipousuario')
                                ->find($idTipouser);
      if(empty($Tipo)){
            $data=array(
                       'code' => 404,
                       'message' => 'Tipo de usuario no encontrado'
                  );
          return new View($data, Response::HTTP_NOT_FOUND);   
        }   
      $query = $em->createQueryBuilder('u')
              ->where('u.usuario = :user and u.idusuario != :id')
              ->setParameters(array( 'user' => $user,
                                      'id' => $id))
              ->getQuery();
      $userName = $query->getResult();
      if($userName){
          $data=array(
                      'code' => 400,
                      'message' => 'Usuario existente'
                );
          return new View($data, Response::HTTP_BAD_REQUEST);        
        } 
      $resultUser->setUsuario($user);
      $salt = microtime();
      $hash = hash("sha256", $pass . $salt, false);
      $resultUser->setPassword($hash);
      $resultUser->setSalt($salt);
      $resultUser->setEstado($esta);
      $resultUser->setIdtipousuario($Tipo);
      $em = $this->getDoctrine()->getManager();
      $em->persist($resultUser);
      $em->flush();
      $data=array(
                  'code' => 200,
                  'message' => 'Usuario actualizado correctamente'
            );
      return new View($data, Response::HTTP_OK);
    }
 
  /**
     * You can get one socio by id_user
     * @ApiDoc(
     *     section = "Users : Users management",
     *     description="Return a socio by id_user",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  requirements={
     *      {"name"="id", "requirement"="true", "dataType"="integer",  "description"="to enter the id of user"}
     *  }
     * )
    * @Rest\Get("/usuario/socio/{id}")
  */
  //Obtener un  socio por idusuario
  public function socioUsuarioAction($id)
    {      
       if(empty($id))
        {
          $datos = array(
                        'code' => 422,
                        'message' => 'No se permiten valores nulos'
                    );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
        }
      $em = $this->getDoctrine()->getRepository('SeguroBundle:Usuario');
      $usuario = $em->findOneBy(array('idusuario' => $id));
      if(empty($usuario)){
              $data=array(
                    'code' => 404,
                    'message' => 'Usuario no encontrado'
                    );
            return new View($data, Response::HTTP_NOT_FOUND);   
      }
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("SELECT a.idafiliado, a.nombre, a.apellido, a.cedula, a.fechanacimiento, a.fechaingreso
                                        , a.sexo, a.parentesco, tu.tipo, a.sexo, a.fechanacimiento, u.foto, u.usuario 
                                  FROM SeguroBundle:Usuario u 
                                       join u.idafiliado a  
                                       join u.idtipousuario tu
                                  WHERE u.idusuario=".$id);
      $socio = $query->getResult(); 
      if ($socio == null) {
          $datos = array(
                'code' => 404,
                'message' => 'No se encontraron datos'
          );
        return new View($datos, Response::HTTP_NOT_FOUND);
      }
      return $vista = $socio;
    }  
  
   /**
     * You can update the photo of the user
     * @ApiDoc(
     *     section = "Users : Users management",
     *     description="Update photo of user",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  requirements={
     *      {"name"="id", "requirement"="true", "dataType"="integer",  "description"="to enter id of the user"}
     *  },
     *  parameters={
     *      {"name"="foto", "dataType"="file", "required"="true", "description"="to enter a photo"}
     *  }
     * ) 
    * @Rest\Put("/usuario/foto/{id}")
  */
   //Actualizar foto del usuario
  public function fotoAction(Request $request, $id)
    {      
      $foto = $request->get('foto');
      if(empty($foto))
        {
          $datos = array(
                        'code' => 422,
                        'message' => 'No se permiten valores nulos'
                    );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
        }
      $em = $this->getDoctrine()->getRepository('SeguroBundle:Usuario');
      $usuario = $em->findOneBy(array('idusuario' => $id));
      if(empty($usuario)){
              $data=array(
                    'code' => 404,
                    'message' => 'Usuario no existente'
                    );
            return new View($data, Response::HTTP_NOT_FOUND);   
      }
      
      $usuario->setFoto('img/'.$foto);
      $em = $this->getDoctrine()->getManager();
      $em->persist($usuario);
      $em->flush();
      $data=array(
                  'code' => 200,
                  'message' => 'Foto actualizada correctamente'
            );
      return new View($data, Response::HTTP_OK);
    }
  
   /**
     * You can update the username of the user
     * @ApiDoc(
     *     section = "Users : Users management",
     *     description="Update username of the user",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  requirements={
     *      {"name"="id", "requirement"="true", "dataType"="integer",  "description"="to enter id of the user"}
     *  },
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"="true", "description"="to enter the new username"}
     *  }
     * ) 
    * @Rest\Put("/usuario/username/{id}")
  */
   //Actualizar solo el username 
  public function userNameAction(Request $request, $id)
    {      
      $username = $request->get('username');
      if(empty($username))
        {
          $datos = array(
                        'code' => 422,
                        'message' => 'No se permiten valores nulos'
                    );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
        }
      $em = $this->getDoctrine()->getRepository('SeguroBundle:Usuario');
      $usuario = $em->findOneBy(array('idusuario' => $id));
      if(empty($usuario)){
              $data=array(
                    'code' => 404,
                    'message' => 'Usuario no existente'
                    );
            return new View($data, Response::HTTP_NOT_FOUND);   
      }
      
      $usuario->setUsuario($username);
      $em = $this->getDoctrine()->getManager();
      $em->persist($usuario);
      $em->flush();
      $data=array(
                  'code' => 200,
                  'message' => 'Usuario actualizado correctamente'
            );
      return new View($data, Response::HTTP_OK);
    } 
 
    /**
     * You can update the password of the user
     * @ApiDoc(
     *     section = "Users : Users management",
     *     description="Update password of the user",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when is not found",
     *         406="Returned when is not acceptable",
     *         422="Returned when the unprocessable entity"
     *     },
     *     tags={
     *         "stable" = "green",
     *     },
     *  requirements={
     *      {"name"="id", "requirement"="true", "dataType"="integer",  "description"="to enter id of the user"}
     *  },
     *  parameters={
     *      {"name"="passworanterior", "dataType"="string", "required"="true", "description"="to enter previous password"},
     *      {"name"="password", "dataType"="string", "required"="true", "description"="to enter the new password"}
     *  }
     * ) 
    * @Rest\Put("/usuario/password/{id}")
  */
   //Actualizar usuario y password socio
  public function apdatecuentasAction(Request $request, $id)
    {
      $pass = $request->get('password');
      $passAnte = $request->get('passworanterior');
      if((empty($pass))|| ( empty($passAnte)))
        {
          $datos = array(
                        'code' => 422,
                        'message' => 'No se permiten valores nulos'
                    );
          return new View($datos, Response::HTTP_UNPROCESSABLE_ENTITY); 
        }
      $em = $this->getDoctrine()->getRepository('SeguroBundle:Usuario');
      $resultUser = $em->find($id);
      if(empty($resultUser)){
            $data=array(
                       'code' => 404,
                       'message' => 'Usuario no encontrado'
                  );
          return new View($data, Response::HTTP_NOT_FOUND);   
        } 
      $salt = $resultUser->getSalt();
      $hash = hash("sha256", $passAnte . $salt, false);
      if ($hash != $resultUser->getPassword()){
          $data=array(
                       'code' => 406,
                       'message' => 'Contraseña anterior no es válida'
                  );
          return new View($data, Response::HTTP_NOT_ACCEPTABLE);   
      }
      $salt = microtime();
      $hash = hash("sha256", $pass . $salt, false);
      $resultUser->setPassword($hash);
      $resultUser->setSalt($salt);
      $em = $this->getDoctrine()->getManager();
      $em->persist($resultUser);
      $em->flush();
      $data=array(
                  'code' => 200,
                  'message' => 'Contraseña actualizada correctamente'
            );
      return new View($data, Response::HTTP_OK);
    }      
 }

 