<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Repository\PersonaRepository;
use Doctrine\ORM\EntityRepository;

class EditUsuario2Type extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',null,array('label' => 'Usuario','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm nombreUsuario'
                    )))   
            ->add('password','repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'La contraseña no son iguales',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => false,
                    'first_options'  => array('label' => 'Contraseña','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm firstPassword'
                    )),
                    'second_options' => array('label' => 'Confirmar contraseña','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm secondPassword'
                    )),
                ))
            //->add('salt')
            //->add('estado')
            /*->add('persona','entity', array( 'label' => 'Empleado','required'=>false,
                         'empty_value'   => 'Seleccione empleado...',
                         'class'         => 'DGPlusbelleBundle:Persona',
                         'query_builder' => function(EntityRepository $r){
                                               return $r->createQueryBuilder('e')
                                                        ->innerJoin('e.empleado', 'p')
                                                        ->where('p.estado = true');
                                            } ,
                         'attr'=>array(
                            'class'=>'form-control empleadoUsuario'
                         )
                       ))*/
            /*->add('user_roles','entity',array('label' => 'Seleccione un rol','required'=>false,
                'class'=>'DGPlusbelleBundle:Rol','property'=>'nombre',
                'multiple'=>true,
                'expanded'=>true,
                    'attr'=>array(
                    'class'=>'rolUsuario'
                    )))*/
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
