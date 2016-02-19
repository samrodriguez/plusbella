<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CierreAdministrativoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('horaInicio', 'time', array(
                    'input'  => 'datetime',
                    'widget' => 'choice',
                    'hours'=> array('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'),
                    'minutes'=> array('0','30')
                ))
            ->add('horaFin', 'time', array(
                    'input'  => 'datetime',
                    'widget' => 'choice',
                    'hours'=> array('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'),
                    'minutes'=> array('0','30')
                ))
            ->add('motivo',null, array(
                    'required'=>false,
                    'attr'=>array('class'=>'form-control input-sm motivoCierre'),
            ))
            ->add('fecha', 'date',
                  array('label'  => 'Fecha','required'=>false,
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'fechaCierre'),
                        'format' => 'dd-MM-yyyy',
                       ))
                
            
            ->add('empleado','entity', array( 'label' => 'Empleado','required'=>false,
                         'empty_value'   => 'Seleccione un empleado...',
                         'class'         => 'DGPlusbelleBundle:Empleado',
                        /* 'query_builder' => function(EntityRepository $r){
                                                return $r->createQueryBuilder('emp')
                                                        ->innerJoin('emp.horario', 'h');
                                                //return $r->seleccionarEmpleadosPersonasActivos();
                                            }, */
                         'query_builder' => function(EntityRepository $repository) {
                                                return $repository->obtenerEmpActivo();
                                             },  
                         'attr'=>array(
                         'class'=>'form-control input-sm busqueda'
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
            'data_class' => 'DGPlusbelleBundle\Entity\CierreAdministrativo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_cierreadministrativo';
    }
}
