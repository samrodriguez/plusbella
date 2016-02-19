<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CierreAdministrativoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('horaInicio', 'time', array(
                    'input'  => 'datetime',
                    'widget' => 'choice',
                    'hours'=> array('06','07','08','09','10','11','12','13','14','15','16','17','18','19','20'),
                    'minutes'=> array('00','30')
                ))
            ->add('horaFin', 'time', array(
                    'input'  => 'datetime',
                    'widget' => 'choice',
                    'hours'=> array('06','07','08','09','10','11','12','13','14','15','16','17','18','19','20'),
                    'minutes'=> array('00','30')
                ))
            ->add('motivo')
            ->add('fecha', null,
                  array('label'  => 'Fecha','required'=>false,
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm fechaCita'),
                        'format' => 'dd-MM-yyyy',
                       ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\CierreAdministrativo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_cierreadministrativo';
    }
}
