<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ComposicionCorporalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('peso')
            ->add('grasaCorporal')
            ->add('aguaCorporal')
            ->add('fecha')
            ->add('masaMusculo')
            ->add('valoracionFisica')
            ->add('edadMetabolica')
            ->add('dciBmr')
            ->add('masaOsea')
            ->add('grasaVisceral')
            ->add('consulta')
            ->add('estetica')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\ComposicionCorporal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_composicioncorporal';
    }
}
