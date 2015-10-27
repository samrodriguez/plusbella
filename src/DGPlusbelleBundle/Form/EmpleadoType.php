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
            ->add('primerNombre', 'text', array(
                  'label'         =>  'Primer nombre',
                  //'empty_value'=>'Seleccione un tipo de servicio',
                  //'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                  'mapped'        => false
                  //'choices' => array()
                ))
            ->add('segundoNombre', 'text', array(
                  'label'         =>  'Segundo Nombre',
                  //'empty_value'=>'Seleccione un tipo de servicio',
                  //'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                  'mapped'        => false
                  //'choices' => array()
                ))
            ->add('primerApellido', 'text', array(
                  'label'         =>  'Primer Apellido',
                  //'empty_value'=>'Seleccione un tipo de servicio',
                  //'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                  'mapped'        => false
                  //'choices' => array()
                ))
            ->add('segundoApellido', 'text', array(
                  'label'         =>  'Segundo Apellido',
                  //'empty_value'=>'Seleccione un tipo de servicio',
                  //'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                  'mapped'        => false
                  //'choices' => array()
                ))
            ->add('casada', 'text', array(
                  'label'         =>  'Apellido de casada',
                  //'empty_value'=>'Seleccione un tipo de servicio',
                  //'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                  'mapped'        => false
                  //'choices' => array()
                ))
            ->add('direccion', 'text', array(
                  'label'         =>  'Direccion',
                  //'empty_value'=>'Seleccione un tipo de servicio',
                  //'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                  'mapped'        => false
                  //'choices' => array()
                ))
	    ->add('telefono', 'text', array(
                  'label'         =>  'Telefono',
                  //'empty_value'=>'Seleccione un tipo de servicio',
                  //'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                  'mapped'        => false
                  //'choices' => array()
                ))
            ->add('email', 'text', array(
                  'label'         =>  'Correo electronico',
                  //'empty_value'=>'Seleccione un tipo de servicio',
                  //'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                  'mapped'        => false
                  //'choices' => array()
                ))
            ->add('cargo')
            ->add('foto')
            ->add('persona')
            ->add('sucursal')
            //->add('horario')
            ->add('tratamiento')
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
