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
            ->add('monto','text',array('label' => 'Monto $','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm montoDevolucion'
                    )))
            ->add('motivo','textarea',array('label' => 'Motivo','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm motivoDevolucion'
                    )))
           // ->add('fechaDevolucion')
            ->add('empleado',null,array('label' => 'Empleado','empty_value'=>'Seleccione empleado','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm empleadoDevolucion'
                    )))
            ->add('paciente',null,array('label' => 'Paciente','empty_value'=>'Seleccione paciente',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('ventapaquete',null, array('label'=>'Paquetes','required'=>false))
            ->add('personatratamiento',null, array('label'=>'Tratamientos','required'=>false))
            ->add('flagDevolucion', 'choice', array('label'=>'Aplicar devolución a',
                    'choices'  => array('0' => 'Paquete', '1' => 'Tratamiento'),
                    'multiple' => false,
                    'expanded'=>'true',
                    'required' => true,
                    'data' => 1
                    
                
                ))
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
