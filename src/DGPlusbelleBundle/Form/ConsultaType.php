<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsultaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaConsulta', null,
                  array('label'  => 'Fecha consulta',
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm'),
                       ))
            ->add('horaInicio')
            ->add('horaFin')
            ->add('observacion','text',array('label' => 'Observacion',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('incapacidad', 'choice', array(
                    'choices'  => array('1' => 'Sí', '0' => 'No'),
                    'multiple' => false,
                'expanded'=>'true',
                    'required' => true,
                    
                
                ))
            //->add('cita')
            ->add('paciente',new PacienteType())
            /*->add('tipoConsulta','entity', array( 'label' => 'Tipo de consulta',
                         'empty_value'   => 'Seleccione un tipo de consulta...',
                         'class'         => 'DGPlusbelleBundle:TipoConsulta',
                         
                         'attr'=>array(
                         'class'=>'form-control'
                         )
                       ))*/
            ->add('tratamiento','entity', array( 'label' => 'Tratamiento',
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:Tratamiento',
                         
                         'attr'=>array(
                         'class'=>'form-control'
                         )
                       ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\Consulta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_consulta';
    }
}
