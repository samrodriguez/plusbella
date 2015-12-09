<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SesionTratamientoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('fechaSesion')
            ->add('horaInicio')
            ->add('horaFin')
            //->add('ventaPaquete')
            ->add('sucursal',null,array('label' => 'Sucursal','required'=>false,
                'empty_value'   => 'Seleccione sucursal...',      
                'attr'=>array(
                'class'=>'form-control input-sm sucursalPaquete'
                    )))      
            //->add('paciente')
            ->add('empleado',null,array('label' => 'Empleado','required'=>false,
                'empty_value'   => 'Seleccione empleado...',      
                'attr'=>array(
                'class'=>'form-control input-sm sesionEmpleado'
                    )))      
            ->add('tratamiento',null, array( 'label' => 'Tratamiento','required'=>false,
                         'empty_value'   => 'Seleccione tratamiento...',     
                         'attr'=>array(
                         'class'=>'form-control input-sm tratamientoPaquete'
                         )))
           /* ->add('fotoAntes',null, array(
                    'label'         =>  'Foto Antes',                               
                    'required'  => false,
                    'mapped' => false
                )) */
            ->add('fileAntes',null, array('label'=>'Foto antes','required'=>false,
                    'attr'=>array('class'=>'fotoAntes'  
                        
                    )))  
            ->add('fileDespues',null, array('label'=>'Foto despues','required'=>false,
                    'attr'=>array('class'=>'fotoDespues' 
                    )))      
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\SesionTratamiento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_sesiontratamiento';
    }
}