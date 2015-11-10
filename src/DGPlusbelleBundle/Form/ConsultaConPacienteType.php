<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsultaConPacienteType extends AbstractType
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
            ->add('horaInicio','time',array('label' => 'Hora Inicio',
                    'attr'=>array(
                    'class'=>' '
                    )))
            ->add('horaFin','time',array('label' => 'Hora Fin',
                    'attr'=>array(
                    'class'=>' '
                    )))
            ->add('observacion','textarea',array('label' => 'Observación',
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('incapacidad', 'choice', array(
                    'choices'  => array('1' => 'Sí', '0' => 'No'),
                    'multiple' => false,
                'expanded'=>'true',
                    'required' => true,
                    
                
                ))
            //->add('cita')
            ->add('paciente','entity', array( 'label' => 'Paciente',
                         'empty_value'   => 'Seleccione un paciente...',
                         'class'         => 'DGPlusbelleBundle:Paciente',
                         'attr'=>array(
                         'class'=>'form-control input-sm'
                         )
                       ))
            ->add('tipoConsulta','entity', array( 'label' => 'Tipo de consulta',
                         'empty_value'   => 'Seleccione un tipo de consulta...',
                         'class'         => 'DGPlusbelleBundle:TipoConsulta',
                         
                         'attr'=>array(
                         'class'=>'form-control'
                         )
                       ))
            ->add('tratamiento','entity', array( 'label' => 'Tratamiento',
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:Tratamiento',
                         
                         'attr'=>array(
                         'class'=>'form-control input-sm'
                         )
                       ))
          /*      
            ->add('producto', 'entity', array(
                    'label'         =>  'Seleccione el producto',
                    'class'         =>  'DGPlusbelleBundle:Producto',
                    'multiple'  => true,
                    'expanded'  => true,
                    'required'  => false,
                    'mapped' => false
                ))    
            ->add('indicaciones', 'text', array(
                    'label'         =>  'Indicaciones',
                    'required'  => false,
                    'mapped' => false
                ))  */
                
            ->add('placas','collection',array(
                'type' => new ConsultaProductoType(),
                'label'=>' ',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
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
