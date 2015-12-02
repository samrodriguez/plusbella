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
            ->add('personaTratamiento', 'entity', 
                  array( 'label'         => 'Tratamiento','required'=>false,
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:PersonaTratamiento',
                         'attr'=>array(
                         'class'=>'form-control input-sm '
                         )
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
