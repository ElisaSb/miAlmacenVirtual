<?php

namespace AlmacenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AlmacenBundle\Entity\Usuario;
use AlmacenBundle\Form\UsuarioType;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UsuarioController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @Route("/usuarios/index", name="index_usuarios")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository("AlmacenBundle:Usuario");
        $usuarios = $usuario_repo->findAll();
        $grupo_repo = $em->getRepository("AlmacenBundle:Grupo");
        $grupos = $grupo_repo->findAll();

        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();

        return $this->render("AlmacenBundle:Usuario:index.html.twig", array(
            "categorias" => $categorias,
            "usuarios" => $usuarios
        ));
    }

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $em = $this->getDoctrine()->getManager();
        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();

        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        
        return $this->render("AlmacenBundle:Usuario:login.html.twig", array(
            "error" => $error,
            "last_username" => $lastUsername,
            "form" => $form->CreateView(),
            "categorias" => $categorias
        ));
    }

    /**
     * @Route("login_check", name="login_check")
     */

    public function validarAction(){

        return $this->redirectToRoute("login");
    }

    /*public function validarAction($email)
    {

        $em = $this->getDoctrine()->getManager();

        //$email = "ana@correo.com";

        $usuario_repo = $em->getRepository("AlmacenBundle:Usuario");
        $usuario_buscado = $usuario_repo->findBy(["email" => $email]);

        if (count($usuario_buscado) == 0) {
            $status_success = "";
            $status_error = "El email es incorrecto o no está registrado";
        } else {
            $status_success = "Encontrado";
            $status_error = "";

            $this->session->getFlashBag()->add('status_success', $status_success);
        }

        $this->session->getFlashBag()->add('status_error', $status_error);

        return $this->redirectToRoute("login");
    }*/

    /**
     * @Route("/logout", name="logout")
     */

    public function logoutAction(Request $request)
    {
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/registro", name="registro")
     */

    public function registroAction(Request $request)
    {

        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);

        $em = $this->getDoctrine()->getManager();
        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $usuario = new Usuario;
                $usuario->setNombre($form->get("nombre")->getData());
                $usuario->setApellidos($form->get("apellidos")->getData());
                $usuario->setEmail($form->get("email")->getData());
                $usuario->setGrupo($form->get("grupo")->getData());

                $factory = $this->get("security.encoder_factory");
                $encoder = $factory->getEncoder($usuario);
                $pass = $encoder->encodePassword($form->get("pass")->getData(), $usuario->getSalt());

                $usuario->setPass($pass);
                $usuario->setRole("ROLE_USER");
                $usuario->setImg(null);

                $em = $this->getDoctrine()->getManager();
                $em->persist($usuario);
                $flush = $em->flush();

                if ($flush == null) {
                    $status_success = "El usuario se ha creado correctamente";
                    $status_error = "";
                } else {
                    $status_success = "";
                    $status_error = "No te has registrado correctamente";
                }
            } else {
                $status_success = "";
                $status_error = "El formulario de registro de usuario no es válido";
            }

            $this->session->getFlashBag()->add("status_success", $status_success);
            $this->session->getFlashBag()->add("status_error", $status_error);
        }

        return $this->render("AlmacenBundle:Usuario:registro.html.twig", array(
            "form" => $form->CreateView(),
            "categorias" => $categorias
        ));
    }

}
