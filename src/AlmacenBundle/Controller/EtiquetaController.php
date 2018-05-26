<?php

namespace AlmacenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AlmacenBundle\Entity\Etiqueta;
use AlmacenBundle\Form\EtiquetaType;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EtiquetaController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    /**
     * @Route("/etiquetas", name="index_etiqueta")
     */

    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $etiqueta_repo = $em->getRepository("AlmacenBundle:Etiqueta");
        $etiquetas = $etiqueta_repo->findAll();

        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();

        return $this->render("AlmacenBundle:Etiqueta:index.html.twig", array(
            "etiquetas" => $etiquetas,
            "categorias" => $categorias
        ));
    }

    /**
     * @Route("/etiqueta/add", name="add_etiqueta")
     */

    public function addAction(Request $request) {

        $etiqueta = new Etiqueta();
        $form = $this->createForm(EtiquetaType::class, $etiqueta);

        $em = $this->getDoctrine()->getManager();
        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $etiqueta = new Etiqueta();
                $etiqueta->setNombre($form->get("nombre")->getData());
                $etiqueta->setDescripcion($form->get("descripcion")->getData());

                $em->persist($etiqueta);
                $flush = $em->flush();

                if ($flush == null) {
                    $status_success = "Etiqueta creada correctamente.";
                    $status_error = "";
                } else {
                    $status_success = "";
                    $status_error = "Error al crear la etiqueta.";
                }

                $this->session->getFlashBag()->add('status_success', $status_success);
                return $this->redirectToRoute("index_etiqueta");
            } else {
                $status_success = "";
                $status_error = "Error: La etiqueta no se ha creado por fallos de validación.";
            }

            $this->session->getFlashBag()->add('status_error', $status_error);
        }

        return $this->render("AlmacenBundle:Etiqueta:add.html.twig", array(
            "form" => $form->createView(),
            "categorias" => $categorias
        ));
    }

    /**
     * @Route("/etiqueta/delete/{id}", name="delete_etiqueta")
     */

    public function deleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $etiqueta_repo = $em->getRepository("AlmacenBundle:Etiqueta");
        $etiqueta = $etiqueta_repo->find($id);
        
        if(count($etiqueta->getProductoEtiqueta()) == 0) {
            $em->remove($etiqueta);
            $em->flush();
        }

        return $this->redirectToRoute("index_etiqueta");
    }
    
    /**
     * @Route("/etiqueta/edit/{id}", name="edit_etiqueta")
     */

    public function editAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $etiqueta_repo = $em->getRepository("AlmacenBundle:Etiquetas");
        $etiqueta = $etiqueta_repo->find($id);
        $etiquetas = $etiqueta_repo->findAll();

        $form = $this->createForm(EtiquetaType::class, $etiqueta);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $etiqueta->setNombre($form->get("nombre")->getData());
                $etiqueta->setDescripcion($form->get("descripcion")->getData());

                $em->persist($etiqueta);
                $flush = $em->flush();

                if ($flush == null) {
                    $status_success = "La etiqueta se ha editado correctamente.";
                    $status_error = "";
                } else {
                    $status_success = "";
                    $status_error = "Error al editar la etiqueta.";
                }

                $this->session->getFlashBag()->add('status_success', $status_success);
                return $this->redirectToRoute("index_etiqueta");
            } else {
                $status_success = "";
                $status_error = "Error: La etiqueta no se ha editado por fallos de validación.";
            }

            $this->session->getFlashBag()->add('status_error', $status_error);
        }

        return $this->render("AlmacenBundle:Etiqueta:edit.html.twig", array(
            "form" => $form->createView(),
            "etiquetas" => $etiquetas
        ));
    }

}
