<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaqueteTratamientoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tratamiento',null,array('label' => 'Tratamiento','required'=>false,'empty_value'=>'Seleccione tratamiento',
                    'attr'=>array(
                    'class'=>'form-control input-sm tratamientoPaquete'
                    )))   
            ->add('numSesiones','text',array('label' => '# Sesiones','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm sesionesPaquete'
                    )))   
           // ->add('paquete')
           
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\PaqueteTratamiento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_paquetetratamiento';
    }
}
