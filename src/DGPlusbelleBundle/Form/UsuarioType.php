<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',null, array(
                    'required' => false
                ))
            ->add('password','repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'La contraseña no son iguales',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => false,
                    'first_options'  => array('label' => 'Contraseña'),
                    'second_options' => array('label' => 'Confirmar contraseña'),
                ))
            //->add('salt')
            //->add('estado')
            ->add('persona')
            ->add('user_roles','entity',array('label' => 'Seleccione un rol','required'=>false,
                'class'=>'DGPlusbelleBundle:Rol','property'=>'nombre',
                'multiple'=>true,
                'expanded'=>true,
                    'attr'=>array(
                    'class'=>'tratamientoEmpleado'
                    )))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\Usuario'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_usuario';
    }
}
