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
            ->add('descripcion','text',array('label' => 'Descripción',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('porcentaje','text',array('label' => 'Porcentaje %',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('meta','text',array('label' => 'Meta $',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
           // ->add('estado')
            ->add('empleado',null,array('label' => 'Empleado',
                    'empty_value'   => 'Seleccione un tipo de consulta...',
                    'class'         => 'DGPlusbelleBundle:Empleado',
                    'query_builder' => function(EntityRepository $repository) {
                      return $repository->obtenerEmpActivo();
                    }, 
                    'empty_value'=>'Seleccione empleado',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
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
