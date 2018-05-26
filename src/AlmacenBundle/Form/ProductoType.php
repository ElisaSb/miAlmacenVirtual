<?php

namespace AlmacenBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductoType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nombre', TextType::class, array(
                    "label" => "Nombre:",
                    "required" => "required",
                    "attr" => array(
                        "class" => "form-nombre form-control"
            )))
                ->add('descripcion', TextareaType::class, array(
                    "label" => "Descripción:",
                    "attr" => array(
                        "class" => "form-descripcion form-control"
            )))
                ->add('img', FileType::class, array(
                    "label" => "Imagen:",
                    "attr" => array(
                        "class" => "form-img form-control"),
                    "data_class" => null,
                    "required" => false
             ))
                ->add('categoria', EntityType::class, array(
                    "class" => "AlmacenBundle:Categoria",
                    "label" => "Categoría:",
                    "attr" => array(
                        "class" => "form-categoria form-control"
            )))
                ->add('etiquetas', TextType::class, array(
                    "mapped" => false,
                    "label" => "Etiquetas:",
                    "attr" => array(
                        "class" => "form-etiquetas form-control"
            )))
            ->add('fechaCaducidad', TextType::class, array(
                    "required" => false,
                    "label" => "Fecha de Caducidad:",
                    "attr" => array(
                        "class" => "form-fechaCaducidad form-control"
            )))
            ->add('ubicacion', TextType::class, array(
                    "required" => false,
                    "label" => "Ubicación:",
                    "attr" => array(
                        "class" => "form-ubicacion form-control"
            )))
            ->add('precio', TextType::class, array(
                    "required" => false,
                    "label" => "Precio:",
                    "attr" => array(
                        "class" => "form-precio form-control"
            )))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array(
                        "class" => "form-submit btn btn-success"
        )));
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AlmacenBundle\Entity\Producto'
        ));
    }

}
