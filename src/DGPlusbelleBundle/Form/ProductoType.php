<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('costo','text',array('label' => 'Costo',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('fechaCompra','date',array('label' => 'Fecha de Compra',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('fechaVencimiento','date',array('label' => 'Fecha de vencimiento',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('estado')
            ->add('categoria')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\Producto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_producto';
    }
}
