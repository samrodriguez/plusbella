<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TratamientoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                
            ->add('categoria',null,array('label' => 'Categoria', 'empty_value'=>'Seleccione Categoria',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))    
            ->add('nombre','text',array('label' => 'Nombre',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('costo','text',array('label' => 'Costo $',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
           // ->add('estado')
            
            //->add('empleado')
           //->add('paquete')
           ->add('sucursal','entity',array('label' => 'Sucursales',
                'class'=>'DGPlusbelleBundle:Sucursal','property'=>'nombre',
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
            'data_class' => 'DGPlusbelleBundle\Entity\Tratamiento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_tratamiento';
    }
}
