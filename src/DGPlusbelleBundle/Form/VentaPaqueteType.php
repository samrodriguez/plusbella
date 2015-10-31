<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Repository\EmpleadoRepository;
use Doctrine\ORM\EntityRepository;

class VentaPaqueteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaVenta', null,
                  array('label'  => 'Fecha de venta',
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm'),
                       ))
            //->add('fechaRegistro')
            ->add('paquete', 'entity', 
                  array( 'label'         => 'Paquete',
                         'empty_value'   => 'Seleccione un paquete...',
                         'class'         => 'DGPlusbelleBundle:Paquete',
                         'attr'=>array(
                         'class'=>'form-control'
                         )
                       ))

            //->add('sucursal')
            ->add('paciente','entity', array( 'label' => 'Paciente',
                         'empty_value'   => 'Seleccione un paciente...',
                         'class'         => 'DGPlusbelleBundle:Persona',
                         'query_builder' => function(EntityRepository $r){
                                                return $r->createQueryBuilder('pa')
                                                        ->innerJoin('pa.paciente', 'p');
                                                //return $r->seleccionarEmpleadosPersonasActivos();
                                            } ,  
                         'attr'=>array(
                         'class'=>'form-control'
                         )
                       ))
            ->add('empleado','entity', array( 'label' => 'Vendido por',
                         'empty_value'   => 'Seleccione un empleado...',
                         'class'         => 'DGPlusbelleBundle:Persona',
                         'query_builder' => function(EntityRepository $r){
                                                return $r->createQueryBuilder('e')
                                                        ->innerJoin('e.empleado', 'p');
                                                //return $r->seleccionarEmpleadosPersonasActivos();
                                            } ,  
                         'attr'=>array(
                            'class'=>'form-control'
                         )
                       ))
            //->add('usuario')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\VentaPaquete'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_ventapaquete';
    }
}