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
            ->add('tratamiento',null,array('label' => 'Tratamiento','empty_value'=>'Seleccione tratamiento',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))   
            ->add('numSesiones','text',array('label' => 'Cantidad de Sesiones',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
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
