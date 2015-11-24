<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Entity\Comision;
use Doctrine\ORM\EntityRepository;

class ComisionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion','text',array('label' => 'Descripción','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm descripcionComision'
                    )))
            ->add('porcentaje','text',array('label' => 'Porcentaje %','required'=>false,
                    'attr'=>array('maxlength' => 5,
                    'class'=>'form-control input-sm porcentajeComision'
                    )))
            ->add('meta','text',array('label' => 'Meta $','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm metaComision'
                    )))
           // ->add('estado')
            ->add('empleado','entity',array('label' => 'Empleado','required'=>false,
                   'empty_value'=>'Seleccione empleado',
                    'class'         => 'DGPlusbelleBundle:Empleado',
                    'query_builder' => function(EntityRepository $repository) {
                      return $repository->obtenerEmpActivo();
                    }, 
                    'attr'=>array(
                    'class'=>'form-control input-sm empleadoComision'
                    )))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\Comision'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_comision';
    }
}
