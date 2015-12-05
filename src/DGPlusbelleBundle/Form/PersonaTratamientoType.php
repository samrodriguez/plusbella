<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Repository\EmpleadoRepository;
use Doctrine\ORM\EntityRepository;
class PersonaTratamientoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('costoConsulta', 'text', array('label'=>'Costo $', 'required'=>false,
                    'attr'=>array(
                         'class'=>'form-control input-sm costoConsulta'
                         )
                       ))
            ->add('numSesiones', null,
                  array('label'  => '# Sesiones','required'=>false,
                        'attr'   => array('class' => 'form-control input-sm sesionesTratamiento'),
                       )) 
            ->add('paciente','entity', array( 'label' => 'Paciente','required'=>false,
                         'empty_value'   => 'Seleccione un paciente...',
                         'class'         => 'DGPlusbelleBundle:Persona',
                         'query_builder' => function(EntityRepository $r){
                                                return $r->createQueryBuilder('pa')
                                                        ->innerJoin('pa.paciente', 'p');
                                                //return $r->seleccionarEmpleadosPersonasActivos();
                                            } ,  
                         'attr'=>array(
                         'class'=>'form-control input-sm pacienteTratamiento'
                         )
                       ))
            ->add('empleado','entity', array( 'label' => 'Vendido por','required'=>false,
                         'empty_value'   => 'Seleccione un empleado...',
                         'class'         => 'DGPlusbelleBundle:Persona',
                         'query_builder' => function(EntityRepository $r){
                                                return $r->createQueryBuilder('e')
                                                        ->innerJoin('e.empleado', 'p')
                                                        ->where('p.estado = true');
                                                //return $r->seleccionarEmpleadosPersonasActivos();
                                            } ,
                         'attr'=>array(
                            'class'=>'form-control input-sm empleadoVentaPaquete'
                         )
                       ))
            ->add('tratamiento','entity', array( 'label' => 'Tratamiento','required'=>false,
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:Tratamiento',
                         'query_builder' => function(EntityRepository $repository) {
                            return $repository->obtenerTratActivo();
                       },     
                         'attr'=>array(
                         'class'=>'form-control input-sm tratamientoPaciente'
                         )
                       ))
            /*->add('fechaVenta', null,
                  array('label'  => 'Fecha de venta','required'=>false,
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm calZebra'),
                       )) */
             ->add('cuotas', null, array('label'=>'Cuotas', 'required'=>false,
                    'attr'=>array(
                         'class'=>'form-control input-sm cuotasTratamiento'
                         )
                       ))
             ->add('descuento', null, 
                  array( 'label'         => 'Descuento','required'=>false,
                         'empty_value'   => 'Seleccione un descuento...',
                         'class'         => 'DGPlusbelleBundle:Descuento',
                         'query_builder' => function(EntityRepository $repository) {
                           return $repository->obtenerDescActivo();
                       },
                         'attr'=>array(
                         'class'=>'form-control input-sm descuentoCita'
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
            'data_class' => 'DGPlusbelleBundle\Entity\PersonaTratamiento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_personatratamiento';
    }
}
