<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Entity\Empleado;
use DGPlusbelleBundle\Entity\Paciente;
use DGPlusbelleBundle\Entity\VentaPaquete;
use Doctrine\ORM\EntityRepository;

class AbonoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder
            ->add('monto',null,array('label' => 'Monto',
                    'attr'=>array(
                    'class'=>'form-control input-sm '
                    )))
            ->add('descripcion','textarea',array('label' => 'Descripción',
                    'attr'=>array(
                    'class'=>'form-control input-sm '
                    )))
            //->add('fechaAbono')
            ->add('empleado','entity', array( 'label' => 'Empleado','required'=>false,
                         'empty_value'   => 'Seleccione un empleado...',
                         'class'         => 'DGPlusbelleBundle:Empleado',
                         'query_builder' => function(EntityRepository $repository) {
                                                return $repository->obtenerEmpActivo();
                                             },  
                         'attr'=>array(
                         'class'=>'form-control input-sm '
                         )
                       ))
            ->add('paciente', null, 
                  array( 'label'         => 'Paciente',
                         'empty_value'   => 'Seleccione un paciente...',
                         'class'         => 'DGPlusbelleBundle:Paciente',
                         'attr'=>array(
                         'class'=>'form-control input-sm '
                         )
                       ))
            ->add('ventaPaquete')
            ->add('personaTratamiento')
            ->add('flagAbono', 'choice', array('label'=>'Aplicar abono a',
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
            'data_class' => 'DGPlusbelleBundle\Entity\Abono'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_abono';
    }
}
