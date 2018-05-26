<?php

namespace AlmacenBundle\Repository;

use AlmacenBundle\Entity\Etiqueta;
use AlmacenBundle\Entity\ProductoEtiqueta;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductoRepository extends \Doctrine\ORM\EntityRepository {

    public function guardarProductoEtiquetas($etiquetas = null, $nombre = null, $categoria = null, $usuario = null, $producto = null) {
        $em = $this->getEntityManager();

        $etiqueta_repo = $em->getRepository("AlmacenBundle:Etiqueta");

        if ($producto == null) {
            $producto = $this->findOneBy(array(
                "nombre" => $nombre,
                "categoria" => $categoria,
                "usuario" => $usuario
            ));
        } else {
            
        }

        //$etiquetas -= ",";

        $etiquetas = explode(",", $etiquetas);

        foreach ($etiquetas as $etiqueta) {
            $isset_etiqueta = $etiqueta_repo->findOneBy(array(
                "nombre" => $etiqueta
            ));

            if (count($isset_etiqueta) == 0) {
                $etiqueta_obj = new Etiqueta();
                $etiqueta_obj->setNombre($etiqueta);
                $etiqueta_obj->setDescripcion($etiqueta);

                //if(!empty(trim($etiqueta))) {
                $em->persist($etiqueta_obj);
                $em->flush();
                //}
            }

            $etiqueta = $etiqueta_repo->findOneBy(array(
                "nombre" => $etiqueta
            ));

            $productoEtiqueta = new ProductoEtiqueta();
            $productoEtiqueta->setProducto($producto);
            $productoEtiqueta->setEtiqueta($etiqueta);

            $em->persist($productoEtiqueta);
        }

        $flush = $em->flush();

        return $flush;
    }

    public function getPaginaProductos($numeroPagina=5, $currentPagina=1){
        $em = $this->getEntityManager();

        $dql = "SELECT p FROM AlmacenBundle\Entity\Producto p ORDER BY p.id DESC";

        $query = $em->createQuery($dql)
            ->setFirstResult($numeroPagina*($currentPagina-1))
            ->setMaxResults($numeroPagina);

        $paginador = new Paginator($query, $fetchJoinCollection = true);
        return $paginador;
    }

    public function getCategoriaProductos($categoria, $paginaSize=3, $currentPagina=1){

        $em = $this->getEntityManager();

        $dql = "SELECT p FROM AlmacenBundle\Entity\Producto p WHERE p.categoria = :categoria ORDER BY p.id DESC";

        $query = $em->createQuery($dql)
            ->setParameter("categoria", $categoria)
            ->setFirstResult($paginaSize*($currentPagina-1))
            ->setMaxResults($paginaSize);

        $paginador = new Paginator($query, $fetchJoinCollection = true);
        return $paginador;
    }

}
