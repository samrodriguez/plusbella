<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Entity\Categoria;
use Doctrine\ORM\EntityRepository;

class ProductoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoria',null,array('label' => 'Categoria','required'=>false,
                'class'=>'DGPlusbelleBundle:Categoria',
                'query_builder' => function(EntityRepository $repository) {
                   return $repository->obtenerCatActivo();
                 },
                 'empty_value'=>'Seleccione Categoria',
                 'attr'=>array(
                 'class'=>'form-control input-sm categoriaProducto'
               )))       
            ->add('nombre','text',array('label' => 'Nombre','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm nombreProducto'
                    )))
            ->add('costo','text',array('label' => 'Costo $','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm costoProducto'
                    )))
            ->add('fechaCompra', null,
                  array('label'  => 'Fecha de compra','required'=>false,
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm calZebra'),
                       ))
            ->add('fechaVencimiento', null,
                  array('label'  => 'Fecha de vencimiento','required'=>false,
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm calZebra'),
                       ))
            ->add('cantidad',null,array('label' => 'Cantidad','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm cantidadProducto'
                    )))             
            //->add('estado')
           
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
