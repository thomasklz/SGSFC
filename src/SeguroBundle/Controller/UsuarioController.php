<?php

namespace SeguroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use SeguroBundle\Form\UsuarioType;
use SeguroBundle\Entity\Usuario;
use SeguroBundle\MyClass\uri;
use SeguroBundle\MyClass\functiones;



class UsuarioController extends Controller
{
  /**
    * @Route("/perfil", name="pagina_principal")
  */
  public function controlAction(Request $request)
    {
      $session = $request->getSession();
        if ($session->has("user")){
             $id = $this->get('session')->get('idusuario');
             $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);
             $responseSocio = $cliente->request('GET', 'usuario/socio/'.$id);
             $socio = functiones::DecoJson($responseSocio);
             $session->set('idafiliado', $socio[0]['idafiliado']);
             $responsePagos = $cliente->request('GET', 'usuario/pagos/'.$socio[0]['idafiliado']);
             if ($responseSocio->getStatusCode()==200)
              {
                if ($responsePagos->getStatusCode()==200)
                 {
                    $pagos = functiones::DecoJson($responsePagos);
                    $session->set('foto', $socio[0]['foto']);
                    return $this->render('SeguroBundle:Usuario:perfil.html.twig', 
                                   array('datosSocio'=> $socio, 'pagosUltimos'=> $pagos));
                }else{
                    $session->set('foto', $socio[0]['foto']);
                    return $this->render('SeguroBundle:Usuario:perfil.html.twig', 
                                   array('datosSocio'=> $socio));
                }
              }else{
                 $session->set('foto', $socio['foto']);
                 return $this->render('SeguroBundle:Usuario:perfil.html.twig');
              }
             
        }else{
            return $this->redirectToRoute('user_login');
        }
    }
  /**
    * @Route("/login", name="Control_login")
  */
  public function loginAction(Request $request)
    { 
      if ($request->getMethod()=="POST")
      {         
        $user=$request->get("txtusuario");
        $password=$request->get("txtpassword");
        $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                     'headers'=> ['Content-Type'=>'application/json'],
                    ]);
        $response = $cliente->request('POST', 'logearse',
                            ['query' => ['user' => $user, 'password' => $password]]);
        $response_body = functiones::DecoJson($response);
        if ($response->getStatusCode()==200)
        {
            $session = $request->getSession();
            foreach($response_body as $posicion=>$datos)
              {
                 $session->set('user', $datos['usuario']);
                 $session->set('idtipousuario', $datos['idtipousuario']);
                 $session->set('idusuario', $datos['idusuario']);
              }
          return $this->redirectToRoute('pagina_principal'); 
        }else 
        {
          $this->addFlash('incorrecto', 'Credenciales no  válidas');
          return $this->render('SeguroBundle:Usuario:login.html.twig');
        }
      }
      return $this->render('SeguroBundle:Usuario:login.html.twig');
    }
  /**
  * @Route("/logout", name="user_logout")
  */
  public function logoutAction(Request $request)
    {
      $session = $request->getSession();
      $session->clear();
      return $this->redirectToRoute('user_login');
    }
  /**
  * @Route("usuario/listado", name="lista_usuarios")
  * @Method({"Get"})
  */
  public function listAction()
    {
      $rol= $this->get('session')->get('idtipousuario');
      if (($rol['tipo'] != 'ROLE_ADMIN')) {
              return $this->render('SeguroBundle:Default:index.html.twig');
      }else{
          $cliente = new Client(['base_uri' => uri::RUTA_API]);
          $response = $cliente->request('GET', 'usuarios');
          $arrays = functiones::DecoJson($response);
          return $this->render('SeguroBundle:Usuario:listausuarios.html.twig', array('res'=> $arrays));
      }  
    }
  /**
  * @Route("usuario/nuevo", name="nuevo_usuarios")
  */
  public function nuevoAction(Request $request)
    {
      global $idtipo;
      $usuario = new Usuario();
      $form = $this->createForm(UsuarioType::class, $usuario, array(
                'action' => $this->generateUrl('nuevo_usuarios'),
                'method'=> 'Post'
                ));
      if ($request->getMethod()=="POST")
          { 
            $form->handleRequest($request);
            if ($form->isSubmitted() and $form->isValid()) 
            {
              $user=$form->get('usuario')->getData();
              $password= $form->get('password')->getData();
              $idtipousuario=$form->get('idtipousuario')->getData();
              $idtipo=$idtipousuario->getIdtipousuario();
              $cliente = new Client([
                     'base_uri' => uri::RUTA_API,
                     'exceptions' => false,
                    ]);
              $response = $cliente->request('POST', 'usuario/',
                          ['query' => ['usuario' => $user, 'password' => $password,'idtipousuario' => $idtipo ]]);
              if ($response->getStatusCode()==201)
              {
                $this->addFlash('correcto', 'Se registró un nuevo usuario');
                return $this->redirectToRoute('lista_usuarios');
              }
              else
              {
                $response_body = functiones::DecoJson($response);
                $message = functiones::DecoMessage($response_body);
                $this->addFlash('incorrecto', $message );
                return $this->render('SeguroBundle:Usuario:nuevousuario.html.twig', array('form' => $form->createView()));
              }
            }
         }
         return $this->render('SeguroBundle:Usuario:nuevousuario.html.twig', array('form' => $form->createView()));
   }   
  /**
  * @Route("usuario/editar/{id}", name="editar_usuarios")
  * @Method ({"Get"})
  */
  public function editarAction($id)
    {
      $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false,]);    
      $response = $cliente->request('GET', 'usuario/'.$id);
      $response_body = functiones::DecoJson($response);
      if ($response->getStatusCode()==200){
          return $this->render('SeguroBundle:Usuario:editUsuario.html.twig', array('user' => $response_body));
        }
    }
  /**
  * @Route("usuario/editars/{id}", name="update_usuario")
  * @Method ({"Put"})
  */
  public function updateAction(Request $request, $id)
    {
      if ($request->getMethod()=="PUT")
      {         
        $user=$request->get("usuario");
        $tipouser=$request->get("tipouser");
        $password=$request->get("password");
        $estado=$request->get("estado");
        $cliente = new Client(['base_uri' => uri::RUTA_API,
                               'exceptions' => false,]);    
        $response = $cliente->request('PUT', 'usuario/'.$id,
        ['query' => ['usuario' => $user, 'password' => $password,'estado' => $estado,'idTipouser' => $tipouser ]]);
        $response_body = functiones::DecoJson($response);
        if ($response->getStatusCode()==200){
            $this->addFlash('correcto',"Usuario actualizado correctamente");
            return $this->redirectToRoute('lista_usuarios');
        }else
        { 
          $message = functiones::DecoMessage($response_body);
          $this->addFlash("incorrecto",$message); 
          return $this->redirectToRoute('editar_usuarios', array('id' => $id));   
        }
      }
    }
   /**
  * @Route("usuario/foto/{id}", name="update_foto")
  */
  public function updateFotoAction(Request $request, $id)
    {     
      if ($request->getMethod()=="POST")
      {
        $file=$request->files->get('foto');
        $ext= $file->guessExtension();
        if (($ext=="jpg") || ($ext=="png") || ($ext=="jpeg")){
          $file_name=time().".".$ext;
          $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);    
          $response = $cliente->request('PUT', 'usuario/foto/'.$id,
                                               ['query' => ['foto' => $file_name]]);
          $response_body = functiones::DecoJson($response);
          if ($response->getStatusCode()==200){
                $file->move("img", $file_name);
                $this->addFlash('correcto',"Foto actualizada correctamente");
                return $this->redirectToRoute('pagina_principal');
          }else
          { 
              $message = functiones::DecoMessage($response_body);
              $this->addFlash("incorrecto",$message); 
              return $this->redirectToRoute('pagina_principal');   
          }
        }else{
             $this->addFlash("incorrecto","Formato no permitido"); 
             return $this->redirectToRoute('pagina_principal');   
        }
      } 
    }
  /**
  * @Route("usuario/password/{id}", name="update_password")
  */
  public function updatePasswordAction(Request $request, $id)
    {  
      if ($request->getMethod()=="POST")
      {
        $passante=$request->get('passante');
        $password=$request->get('password');
        $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);    
        $response = $cliente->request('PUT', 'usuario/password/'.$id,
                                               ['query' => ['password' => $password,'passworanterior' => $passante]]);
        $response_body = functiones::DecoJson($response);
        if ($response->getStatusCode()==200){
              $this->addFlash('correcto',"Password actualizado correctamente");
              return $this->redirectToRoute('pagina_principal');
        }else
        { 
            $message = functiones::DecoMessage($response_body);
            $this->addFlash("incorrecto",$message); 
            return $this->redirectToRoute('pagina_principal');   
        }
      }
    }
  /**
  * @Route("usuario/username/{id}", name="update_username")
  */
  public function updateUserAction(Request $request, $id)
    {  
      if ($request->getMethod()=="POST")
      {
        $username=$request->get('usuarioS');
        $cliente = new Client(['base_uri' => uri::RUTA_API,'exceptions' => false]);    
        $response = $cliente->request('PUT', 'usuario/username/'.$id,
                                               ['query' => ['username' => $username]]);
        $response_body = functiones::DecoJson($response);
        if ($response->getStatusCode()==200){
              $this->addFlash('correcto',"Usuario actualizado correctamente");
              $session = $request->getSession();
              $session->set('user', $username);
              return $this->redirectToRoute('pagina_principal');
        }else
        { 
            $message = functiones::DecoMessage($response_body);
            $this->addFlash("incorrecto",$message); 
            return $this->redirectToRoute('pagina_principal');   
        }
      }
    }    
}
