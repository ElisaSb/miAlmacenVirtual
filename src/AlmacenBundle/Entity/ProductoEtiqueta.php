<?php

namespace AlmacenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductoEtiqueta
 *
 * @ORM\Table(name="producto_etiqueta", indexes={@ORM\Index(name="fk_producto_etiqueta_productos", columns={"producto_id"}), @ORM\Index(name="fk_producto_etiqueta_etiquetas", columns={"etiqueta_id"})})
 * @ORM\Entity
 */
class ProductoEtiqueta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Producto
     *
     * @ORM\ManyToOne(targetEntity="Producto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     * })
     */
    private $producto;

    /**
     * @var \Etiqueta
     *
     * @ORM\ManyToOne(targetEntity="Etiqueta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etiqueta_id", referencedColumnName="id")
     * })
     */
    private $etiqueta;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set producto
     *
     * @param \AlmacenBundle\Entity\Producto $producto
     *
     * @return ProductoEtiqueta
     */
    public function setProducto(\AlmacenBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \AlmacenBundle\Entity\Producto
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set etiqueta
     *
     * @param \AlmacenBundle\Entity\Etiqueta $etiqueta
     *
     * @return ProductoEtiqueta
     */
    public function setEtiqueta(\AlmacenBundle\Entity\Etiqueta $etiqueta = null)
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    /**
     * Get etiqueta
     *
     * @return \AlmacenBundle\Entity\Etiqueta
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }
}
