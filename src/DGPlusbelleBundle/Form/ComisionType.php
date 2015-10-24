<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
                    'class'=>'form-control'
                    )))
            ->add('porcentaje','text',array('label' => 'Porcentaje %',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('meta','text',array('label' => 'Meta $',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('estado')
           // ->add('empleado')
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
