<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Entity\Sucursal;
use Doctrine\ORM\EntityRepository;

class TratamientoType extends AbstractType
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
                    'style'=>'width:100%;',
                'class'=>'form-control input-sm categoriaTratamiento'
              )))    
            ->add('nombre','text',array('label' => 'Nombre','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm nombreTratamiento'
                    )))
            ->add('costo','text',array('label' => 'Costo $','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm costoTratamiento'
                    )))
           // ->add('estado')
            
            //->add('empleado')
           //->add('paquete')
           ->add('sucursal','entity',array('label' => 'Sucursales','required'=>false,
                'class'=>'DGPlusbelleBundle:Sucursal',
                'query_builder' => function(EntityRepository $repository) {
                  return $repository->obtenerSucActivo();
                },
                'property'=>'nombre',
                'multiple'=>true,
                'expanded'=>true,
                    'attr'=>array(
                    'class'=>'sucursalTratamiento'
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
