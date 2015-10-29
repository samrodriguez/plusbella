<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SucursalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre',
                    'attr'=>array(
                    'class'=>'form-control input-sm '
                    )))
            ->add('direccion','text',array('label' => 'Dirección',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('telefono','text',array('label' => 'Telefono',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
           // ->add('estado')
            ->add('slug','text',array('label' => 'Slug',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
           // ->add('paquete')
           // ->add('tratamiento')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\Sucursal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_sucursal';
    }
}
