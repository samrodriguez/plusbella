<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Form\PersonaType;

class EmpleadoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('persona', new PersonaType())
                
            ->add('cargo','choice',array('label' => 'Cargo','required'=>false, 'empty_value'=>'Seleccione cargo...',
                    'choices'  => array('Esteticista' => 'Esteticista', 'Medico' => 'Medico', 'Secretaria' => 'Secretaria'),
                    'attr'=>array(
                    'class'=>'form-control input-sm cargoEmpleado'
                    )))
        /*    ->add('foto','text',array('label' => 'Foto',
                    'attr'=>array(
                    'class'=>'form-control '
                      
                    )))  */
                
            ->add('porcentaje','text',array('label' => 'Porcentaje %','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm porcentajeEmpleado'
                    )))  
            ->add('meta','text',array('label' => 'Meta $','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm metaEmpleado'
                    )))  
            ->add('bono','text',array('label' => 'Bono $','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm bonoEmpleado'
                    )))      
            //->add('persona')
            ->add('sucursal',null,array('label' => 'Sucursal','required'=>false,'empty_value'=>'Seleccione Sucursal...',
                    'attr'=>array(
                    'class'=>'form-control input-sm sucursalEmpleado'
                    )))
            //->add('horario')
            ->add('tratamiento','entity',array('label' => 'Tratamientos que realiza','required'=>false,
                'class'=>'DGPlusbelleBundle:Tratamiento','property'=>'nombre',
                'multiple'=>true,
                'expanded'=>true,
                    'attr'=>array(
                    'class'=>'tratamientoEmpleado'
                    )))
            ->add('file',null, array('label'=>'Foto de perfil',
                    'attr'=>array('class'=>'tratamientoEmpleado'
                    )))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\Empleado'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_empleado';
    }
}
