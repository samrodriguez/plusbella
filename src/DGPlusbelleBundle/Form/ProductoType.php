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
            ->add('categoria',null,array('label' => 'Categoria',
                'class'=>'DGPlusbelleBundle:Categoria',
                'query_builder' => function(EntityRepository $repository) {
                   return $repository->obtenerCatActivo();
                 },
                 'empty_value'=>'Seleccione Categoria',
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
            ->add('fechaCompra', null,
                  array('label'  => 'Fecha de compra',
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm'),
                       ))
            ->add('fechaVencimiento', null,
                  array('label'  => 'Fecha de vencimiento',
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm'),
                       ))
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
