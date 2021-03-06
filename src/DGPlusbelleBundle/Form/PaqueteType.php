<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Entity\Sucursal;
use DGPlusbelleBundle\Entity\Tratamiento;
use Doctrine\ORM\EntityRepository;

class PaqueteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm nombrePaquete'
                    )))
            ->add('costo','text',array('label' => 'Costo $','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm costoPaquete'
                    )))
         //->add('estado')
       /*     ->add('tratamiento','entity',array('label' => 'Tratamientos',
                'class'=>'DGPlusbelleBundle:Tratamiento',
                'query_builder' => function(EntityRepository $repository) {
                  return $repository->obtenerTratActivo();
                },
                'property'=>'nombre',
                'multiple'=>true,
                'expanded'=>true,
                    'attr'=>array(
                    'class'=>''
                    ))) 
             */    
                
          ->add('sucursal','entity',array('label' => 'Seleccione sucursales','required'=>false,
                'class'=>'DGPlusbelleBundle:Sucursal',
                'query_builder' => function(EntityRepository $repository) {
                  return $repository->obtenerSucActivo();
                },
                'property'=>'nombre',
                'multiple'=>true,
                'expanded'=>true,
                    'attr'=>array(
                    'class'=>'sucursalPaquete'
                    )))      
              
            ->add('placas','collection',array(
                'type' => new PaqueteTratamientoType(),
                'label'=>' ',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'attr'=>array(
                'class'=>'paqueteTratamiento'
                )))                  
                        
          
                
         
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\Paquete'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_paquete';
    }
}
