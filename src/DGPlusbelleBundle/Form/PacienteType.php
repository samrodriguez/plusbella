<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Form\PersonaType;
class PacienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('persona', new PersonaType())
         
                
            ->add('dui','text',array('label' => 'DUI','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('estadoCivil','choice',array('label' => 'Estado Civil','required'=>false, 'empty_value'=>'Seleccione estado civil...',
                    'choices'  => array('s' => 'Soltero', 'c' => 'Casado', 'e' => 'Estudiante'),
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('sexo','choice',array('label' => 'Sexo','required' => false,'empty_value'=>'Seleccione sexo...',
                    'choices'  => array('m' => 'Masculino', 'f' => 'Femenino'),
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            
            ->add('ocupacion','text',array('label' => 'Ocupación','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('lugarTrabajo','text',array('label' => 'Lugar de Trabajo','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('fechaNacimiento', null,
                  array('label'  => 'Fecha nacimiento','required'=>false,
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm'),
                       ))
            ->add('referidoPor','text',array('label' => 'Referido por','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('personaEmergencia','text',array('label' => 'En caso de Emergencia llamar a','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('telefonoEmergencia','text',array('label' => 'Al telefono','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            //->add('estado')
           // ->add('persona')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGPlusbelleBundle\Entity\Paciente'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_paciente';
    }
}
