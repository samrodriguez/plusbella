<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CitaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaCita', null,
                  array('label'  => 'Fecha cita',
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm'),
                       ))
            ->add('horaInicio', null,
                  array('label'  => 'Hora',
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm'),
                       ))
            //->add('horaFin')
           // ->add('fechaRegistro')
            //->add('estado')
            ->add('descuento', null, 
                  array( 'label'         => 'Descuento',
                         'empty_value'   => 'Seleccione un descuento...',
                         'class'         => 'DGPlusbelleBundle:Descuento',
                         'attr'=>array(
                         'class'=>'form-control'
                         )
                       ))
            ->add('empleado','entity', array( 'label' => 'Empleado',
                         'empty_value'   => 'Seleccione un empleado...',
                         'class'         => 'DGPlusbelleBundle:Empleado',
                         'query_builder' => function(EntityRepository $r){
                                                return $r->createQueryBuilder('emp')
                                                        ->innerJoin('emp.horario', 'h');
                                                //return $r->seleccionarEmpleadosPersonasActivos();
                                            } ,  
                         'attr'=>array(
                         'class'=>'form-control busqueda'
                         )
                       ))
            /*->add('horario', null, 
                  array( 'label'         => 'Horario',
                         'empty_value'   => 'Seleccione un día...',
                         'attr'=>array(
                         'class'=>'form-control'
                         )
                       ))*/
            ->add('paciente', null, 
                  array( 'label'         => 'Paciente',
                         'empty_value'   => 'Seleccione un paciente...',
                         'class'         => 'DGPlusbelleBundle:Paciente',
                         'attr'=>array(
                         'class'=>'form-control'
                         )
                       ))
            ->add('tratamiento', null, 
                  array( 'label'         => 'Paquete',
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:Tratamiento',
                         'attr'=>array(
                         'class'=>'form-control'
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
            'data_class' => 'DGPlusbelleBundle\Entity\Cita'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_cita';
    }
}
