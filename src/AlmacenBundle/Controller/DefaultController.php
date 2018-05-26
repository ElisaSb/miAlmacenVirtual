<?php

namespace AlmacenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        //COMPROBAR PRODUCTOS
//        $em = $this->getDoctrine()->getEntityManager();
//        $producto_repo = $em->getRepository("AlmacenBundle:Producto");
//        $productos = $producto_repo->findAll();
//
//        foreach ($productos as $producto){
//            echo $producto->getNombre()."<br/>";
//            echo $producto->getCategoria()->getNombre()."<br/>";
//            echo $producto->getUsuario()->getNombre()."<br/>";
//
//            $etiquetas = $producto->getProductoEtiqueta();
//            foreach ($etiquetas as $etiqueta){
//                echo $etiqueta->getEtiqueta()->getNombre().", ";
//            }
//
//            echo "<hr/>";
//        }
//
//        die();

        //COMPROBAR USUARIOS
//        $em = $this->getDoctrine()->getEntityManager();
//        $usuario_repo = $em->getRepository("AlmacenBundle:Usuario");
//        $usuarios = $usuario_repo->findAll();
//
//        foreach ($usuarios as $usuario){
//            echo $usuario->getNombre()."<br/>";
//            echo $usuario->getGrupo()->getNombre()."<br/>";
//            echo "<hr/>";
//        }
//
//        die();

//        CATEGORIAS y productos por categorias
//        $em = $this->getDoctrine()->getEntityManager();
//        $categoria_repo = $em->getRepository("AlmacenBundle:Categoria");
//        $categorias = $categoria_repo->findAll();
//
//        foreach ($categorias as $categoria){
//            echo $categoria->getNombre()."<br/>";
//
//            $productos = $categoria->getProductos();
//            foreach ($productos as $producto){
//                echo $producto->getNombre().", ";
//            }
//
//            echo "<hr/>";
//        }
//
//        die();

//        GRUPOS y usuarios por grupos
//        $em = $this->getDoctrine()->getEntityManager();
//        $grupo_repo = $em->getRepository("AlmacenBundle:Grupo");
//        $grupos = $grupo_repo->findAll();
//
//        foreach ($grupos as $grupo){
//            echo $grupo->getNombre()."<br/>";
//
//            $usuarios = $grupo->getUsuarios();
//            foreach ($usuarios as $usuario){
//                echo $usuario->getNombre();
//            }
//
//            echo "<hr/>";
//        }
//
//        die();

        //ETIQUETAS y productos por etiquetas
//        $em = $this->getDoctrine()->getEntityManager();
//        $etiquetas_repo = $em->getRepository("AlmacenBundle:Etiqueta");
//        $etiquetas = $etiquetas_repo->findAll();
//
//        foreach ($etiquetas as $etiqueta){
//            echo $etiqueta->getNombre()."<br/>";
//
//            $productoEtiqueta = $etiqueta->getProductoEtiqueta();
//            foreach ($productoEtiqueta as $producto){
//                echo $producto->getProducto()->getNombre();
//            }
//
//            echo "<hr/>";
//        }
//
//        die();
        return $this->render('AlmacenBundle:Default:index.html.twig');
    }
}
