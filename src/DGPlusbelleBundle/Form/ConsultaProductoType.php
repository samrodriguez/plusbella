<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsultaProductoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
           // ->add('consulta')
            ->add('producto',null,array('label' => 'Producto','required' => false, 'empty_value'=>'Seleccione producto...',
                    'attr'=>array(
                    'class'=>'form-control input-sm productoConsulta'
                    )))
                 ->add('indicaciones','textarea',array('label' => 'Indicaciones','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm indicacionesConsulta'
                    )))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\ConsultaProducto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_consultaproducto';
    }
}
