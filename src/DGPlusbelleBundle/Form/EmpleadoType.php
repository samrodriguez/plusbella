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
                
            ->add('cargo','text',array('label' => 'Cargo',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('foto','text',array('label' => 'Foto',
                    'attr'=>array(
                    'class'=>'form-control '
                      
                    )))
            //->add('persona')
            ->add('sucursal',null,array('label' => 'Sucursal','empty_value'=>'Seleccione Sucursal',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            //->add('horario')
            ->add('tratamiento','entity',array('label' => 'Tratamientos que se realizan',
                'class'=>'DGPlusbelleBundle:Tratamiento','property'=>'nombre',
                'multiple'=>true,
                'expanded'=>true,
                    'attr'=>array(
                    'class'=>''
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
