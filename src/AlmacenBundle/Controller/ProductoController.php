<?php

namespace AlmacenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AlmacenBundle\Entity\Producto;
use AlmacenBundle\Form\ProductoType;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductoController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @Route("/{_locale}/{pagina}", name="homepage", defaults={"pagina" = 1, "_locale" =  "es"}, requirements={"_locale" = "en|es"})
     */

    public function indexAction(Request $request, $pagina)
    {
        $em = $this->getDoctrine()->getManager();
        $producto_repo = $em->getRepository("AlmacenBundle:Producto");
        $grupo_repo = $em->getRepository("AlmacenBundle:Grupo");
        $grupos = $grupo_repo->findAll();
        
        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();

        $productosDelGrupo = [];
        $usuarioLogged = $this->getUser();
        $numeroPagina = 5;
        $productos = $producto_repo->getPaginaProductos($numeroPagina, $pagina);

        if ($usuarioLogged != null){
            foreach ($productos as $producto){
                $productoGrupoId = $producto->getUsuario()->getGrupo()->getId();

                if ($productoGrupoId == $usuarioLogged->getGrupo()->getId()){
                    $productosDelGrupo[] = $producto;
                }
            }
        }

        $totalItems = count($productosDelGrupo);
        $pagesCount = ceil($totalItems/$numeroPagina);

        return $this->render("AlmacenBundle:Producto:index.html.twig", array(
            "productos" => $productosDelGrupo,
            "categorias" => $categorias,
            "totalItems" => $totalItems,
            "pagesCount" => $pagesCount,
            "pagina" => $pagina,
            "pagina_m" => $pagina
        ));
    }

    /**
     * @Route("/producto/add", name="add_producto")
     */

    public function addAction(Request $request)
    {

        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);

        $em = $this->getDoctrine()->getManager();
        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
                $producto_repo = $em->getRepository("AlmacenBundle:Producto");
                $categorias = $categoria_repo->findAll();

                $producto = new Producto();
                $producto->setNombre($form->get("nombre")->getData());
                $producto->setDescripcion($form->get("descripcion")->getData());
                $producto->setPrecio($form->get("precio")->getData());
                $producto->setFechaCaducidad($form->get("fechaCaducidad")->getData());
                $producto->setUbicacion($form->get("ubicacion")->getData());

                //Subir fichero
                $fichero = $form["img"]->getData();
                if( !empty($fichero) && $fichero!=null ) {
                    $ext = $fichero->guessExtension();
                    $fichero_nombre = time() . "." . $ext;
                    $fichero->move("uploads", $fichero_nombre);
                    $producto->setImg($fichero_nombre);
                }else{
                    $producto->setImg(null);
                }

                $categoria = $categoria_repo->find($form->get("categoria")->getData());
                $producto->setCategoria($categoria);

                $usuario = $this->getUser();
                $producto->setUsuario($usuario);

                $em->persist($producto);
                $flush = $em->flush();

                $producto_repo->guardarProductoEtiquetas(
                    $form->get("etiquetas")->getData(), $form->get("nombre")->getData(), $categoria, $usuario
                );

                if ($flush == null) {
                    $status_success = "Producto creado correctamente.";
                    $status_error = "";
                } else {
                    $status_success = "";
                    $status_error = "Error al crear el producto.";
                }

                $this->session->getFlashBag()->add('status_success', $status_success);
                return $this->redirectToRoute("homepage");
            } else {
                $status_success = "";
                $status_error = "El formulario de crear producto no es válido.";
            }

            $this->session->getFlashBag()->add('status_error', $status_error);
        }

        return $this->render("AlmacenBundle:Producto:add.html.twig", array(
            "form" => $form->createView(),
            "categorias" => $categorias
        ));
    }

    /**
     * @Route("/producto/delete/{id}", name="delete_producto")
     */

    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $producto_repo = $em->getRepository("AlmacenBundle:Producto");
        $producto_etiqueta_repo = $em->getRepository("AlmacenBundle:ProductoEtiqueta");

        $producto = $producto_repo->find($id);
        $producto_etiquetas = $producto_etiqueta_repo->findBy(array("producto" => $producto));
        foreach ($producto_etiquetas as $et) {
            if (is_object($et)) {
                $em->remove($et);
                $em->flush();
            }
        }

        if (is_object($producto)) {
            $em->remove($producto);
            $em->flush();
        }
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/producto/edit/{id}", name="edit_producto")
     */

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $producto_repo = $em->getRepository("AlmacenBundle:Producto");
        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();

        $producto = $producto_repo->find($id);
        $producto_imagen = $producto->getImg();

        $etiquetas="";
        foreach ($producto->getProductoEtiqueta() as $productoEtiqueta){
            $etiquetas.=$productoEtiqueta->getEtiqueta()->getNombre().",";
        }

        $form = $this->createForm(ProductoType::class, $producto);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                //Subir fichero
                $fichero = $form["img"]->getData();
                if( !empty($fichero) && $fichero!=null ) {
                    $ext = $fichero->guessExtension();
                    $fichero_nombre = time() . "." . $ext;
                    $fichero->move("uploads", $fichero_nombre);

                    $producto->setImg($fichero_nombre);
                } else{
                    $producto->setImg($producto_imagen);
                }

                $categoria = $categoria_repo->find($form->get("categoria")->getData());
                $producto->setCategoria($categoria);

                $usuario = $this->getUser();
                $producto->setUsuario($usuario);

                $em->persist($producto);
                $flush = $em->flush();

                $producto_etiqueta_repo = $em->getRepository("AlmacenBundle:ProductoEtiqueta");

                $producto_etiquetas = $producto_etiqueta_repo->findBy(array("producto" => $producto));
                foreach ($producto_etiquetas as $et) {
                    if (is_object($et)) {
                        $em->remove($et);
                        $em->flush();
                    }
                }

                $producto_repo->guardarProductoEtiquetas(
                    $form->get("etiquetas")->getData(), $form->get("nombre")->getData(), $categoria, $usuario
                );

                if($flush == null){
                    $status_success = "El producto se ha editado correctamente.";
                    $status_error = "";
                } else {
                    $status_success = "";
                    $status_error = "Error al editar el producto.";
                }

                $this->session->getFlashBag()->add('status_success', $status_success);
                return $this->redirectToRoute("homepage");

            } else {
                $status_success = "";
                $status_error = "El formulario de edición de producto no es válido";
            }

            $this->session->getFlashBag()->add('status_error', $status_error);
        }

        return $this->render("AlmacenBundle:Producto:edit.html.twig", array(
            "form" => $form->createView(),
            "producto" => $producto,
            "etiquetas" => $etiquetas,
            "categorias" => $categorias
        ));
    }

    /**
     * @Route("/producto/detalle/{id}", name="detalle_producto")
     */

    public function detallarAction($id){

        $em = $this->getDoctrine()->getManager();
        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
        $categorias = $categoria_repo->findAll();
        $producto_repo = $em->getRepository("AlmacenBundle:Producto");
        $producto = $producto_repo->find($id);

        return $this->render("AlmacenBundle:Producto:detalle.html.twig", array(
            "producto" => $producto,
            "categorias" => $categorias
        ));
    }

}
