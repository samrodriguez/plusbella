<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DevolucionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('monto','text',array('label' => 'Monto $',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('motivo','text',array('label' => 'Motivo',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
           // ->add('fechaDevolucion')
            ->add('empleado',null,array('label' => 'Empleado','empty_value'=>'Seleccione empleado',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('paciente',null,array('label' => 'Paciente','empty_value'=>'Seleccione paciente',
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
            'data_class' => 'DGPlusbelleBundle\Entity\Devolucion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_devolucion';
    }
}
