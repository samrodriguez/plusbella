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
                    'class'=>'form-control'
                    )))
            ->add('estadoCivil','choice',array('label' => 'Estado Civil', 'empty_value'=>'Seleccione estado civil',
                    'choices'  => array('s' => 'Soltero', 'c' => 'Casado', 'e' => 'Estudiante'),
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('sexo','choice',array('label' => 'Sexo','required' => true,'empty_value'=>'Seleccione Sexo',
                    'choices'  => array('m' => 'Masculino', 'f' => 'Femenino'),
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            
            ->add('ocupacion','text',array('label' => 'Ocupación',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('lugarTrabajo','text',array('label' => 'Lugar de Trabajo',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('fechaNacimiento','date',array('label' => 'Fecha de nacimiento',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('referidoPor','text',array('label' => 'Referido por',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('personaEmergencia','text',array('label' => 'En caso de Emergencia llamar a',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('telefonoEmergencia','text',array('label' => 'Al telefono',
                    'attr'=>array(
                    'class'=>'form-control'
                    )))
            ->add('estado')
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
