<?php

namespace AlmacenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AlmacenBundle\Entity\Grupo;
use AlmacenBundle\Form\GrupoType;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GrupoController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    /**
     * @Route("/grupos", name="index_grupo")
     */

    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $grupo_repo = $em->getRepository("AlmacenBundle:Grupo");
        $grupos = $grupo_repo->findAll();

        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();

        return $this->render("AlmacenBundle:Grupo:index.html.twig", array(
            "grupos" => $grupos,
            "categorias" => $categorias
        ));
    }

    /**
     * @Route("/grupo/add", name="add_grupo")
     */

    public function addAction(Request $request) {

        $grupo = new Grupo();
        $form = $this->createForm(GrupoType::class, $grupo);

        $em = $this->getDoctrine()->getManager();
        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $grupo = new Grupo();
                $grupo->setNombre($form->get("nombre")->getData());
                $grupo->setDescripcion($form->get("descripcion")->getData());

                $em->persist($grupo);
                $flush = $em->flush();

                if ($flush == null) {
                    $status_success = "Grupo creado correctamente.";
                    $status_error = "";
                } else {
                    $status_success = "";
                    $status_error = "Error al crear el grupo.";
                }

                $this->session->getFlashBag()->add('status_success', $status_success);
                return $this->redirectToRoute("index_grupo");
            } else {
                $status_success = "";
                $status_error = "Error: El grupo no se ha creado por fallos de validación.";
            }

            $this->session->getFlashBag()->add('status_error', $status_error);
        }

        return $this->render("AlmacenBundle:Grupo:add.html.twig", array(
            "form" => $form->createView(),
            "categorias" => $categorias
        ));
    }

    /**
     * @Route("/grupo/delete/{id}", name="delete_grupo")
     */

    public function deleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $grupo_repo = $em->getRepository("AlmacenBundle:Grupo");
        $grupo = $grupo_repo->find($id);
        
        if(count($grupo->getUsuarios()) == 0) {
            $em->remove($grupo);
            $em->flush();
        }

        return $this->redirectToRoute("index_grupo");
    }
    
    /**
     * @Route("/grupo/edit/{id}", name="edit_grupo")
     */

    public function editAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $grupo_repo = $em->getRepository("AlmacenBundle:Grupo");
        $grupo = $grupo_repo->find($id);
        $grupos = $grupo_repo->findAll();

        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();


        $form = $this->createForm(GrupoType::class, $grupo);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $grupo->setNombre($form->get("nombre")->getData());
                $grupo->setDescripcion($form->get("descripcion")->getData());

                $em->persist($grupo);
                $flush = $em->flush();

                if ($flush == null) {
                    $status_success = "El grupo se ha editado correctamente.";
                    $status_error = "";
                } else {
                    $status_success = "";
                    $status_error = "Error al editar el grupo.";
                }

                $this->session->getFlashBag()->add('status_success', $status_success);
                return $this->redirectToRoute("index_grupo");
            } else {
                $status_success = "";
                $status_error = "Error: El grupo no se ha editado por fallos de validación.";
            }

            $this->session->getFlashBag()->add('status_error', $status_error);
        }

        return $this->render("AlmacenBundle:Grupo:edit.html.twig", array(
            "form" => $form->createView(),
            "grupos" => $grupos,
            "categorias" => $categorias
        ));
    }

}
