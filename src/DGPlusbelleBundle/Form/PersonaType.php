<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombres','text',array('label' => 'Nombres','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            /*->add('segundoNombre','text',array('label' => 'Segundo nombre',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))*/
            ->add('apellidos','text',array('label' => 'Apellidos','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            /*->add('segundoApellido','text',array('label' => 'Segundo apellido',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))*/
            /*->add('apellidoCasada','text',array('label' => 'Apellido Casada',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))*/
            ->add('direccion','text',array('label' => 'Dirección','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('telefono','text',array('label' => 'Telefono','required'=>false,
                    'attr'=>array(
                        'class'=>'form-control input-sm'
                    )))
            ->add('email','text',array('label' => 'Correo','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
           
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\Persona'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_persona';
    }
}
