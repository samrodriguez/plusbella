<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DetallePlantillaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('nombre','text',array('label' => 'Nombre','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm nombreDetalle'
                    )))
            ->add('descripcion','text',array('label' => 'Descripción','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm descripcionDetalle'
                    )))
            ->add('tipoParametro','choice',array('choices'=>array('Radiobutton'=>'Radio button','Checkbox'=>'Checkbox','Textarea'=>'Textarea')))
            //->add('plantilla')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\DetallePlantilla'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_detalleplantilla';
    }
}
