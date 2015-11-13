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
            ->add('nombre','text',array('label' => 'Nombre',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('costo','text',array('label' => 'Costo $',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
         //->add('estado')
            ->add('tratamiento','entity',array('label' => 'Tratamientos',
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
           ->add('sucursal','entity',array('label' => 'Sucursales',
                'class'=>'DGPlusbelleBundle:Sucursal',
                'query_builder' => function(EntityRepository $repository) {
                  return $repository->obtenerSucActivo();
                },
                'property'=>'nombre',
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
