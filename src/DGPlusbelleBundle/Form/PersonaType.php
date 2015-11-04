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
            ->add('primerNombre','text',array('label' => 'Nombres',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            /*->add('segundoNombre','text',array('label' => 'Segundo nombre',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))*/
            ->add('primerApellido','text',array('label' => 'Apellidos',
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
            ->add('direccion','text',array('label' => 'Dirección',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('telefono','number',array('label' => 'Telefono',
                    'attr'=>array(
                        'class'=>'form-control input-sm'
                    )))
            ->add('email','text',array('label' => 'Correo',
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
