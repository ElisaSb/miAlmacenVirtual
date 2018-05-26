<?php

namespace AlmacenBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UsuarioType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->add('nombre', TextType::class, array("label"=>"Nombre:", "required" => "required", "attr" => array(
        "class" => "form-nombre form-control"
        )))
        ->add('apellidos', TextType::class, array("label"=>"Apellidos:", "required" => "required", "attr" => array(
        "class" => "form-apellidos form-control"
        )))
        ->add('email', EmailType::class, array("label"=>"Email:", "required" => "required", "attr" => array(
        "class" => "form-email form-control"
        )))
        ->add('pass', PasswordType::class, array("label"=>"Contraseña:", "required" => "required", "attr" => array(
        "class" => "form-pass form-control"
        )))
        ->add('grupo', EntityType::class, [
        "class" => "AlmacenBundle:Grupo",
        "label" => "Grupo:",
        "attr" => [
            "class" => "form-grupo form-control"
        ]])
        ->add('Guardar', SubmitType::class, array("attr" => array(
        "class" => "form-submit btn btn-success"
        )));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AlmacenBundle\Entity\Usuario'
        ));
    }

}
