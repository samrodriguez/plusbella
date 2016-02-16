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
                    'class'=>'form-control input-sm duiPaciente'
                    )))
            ->add('estadoCivil','choice',array('label' => 'Estado Civil','required'=>false, 'empty_value'=>'Seleccione estado civil...',
                    'choices'  => array('Soltero/a' => 'Soltero/a', 'Casado/a' => 'Casado/a', 'Divorciado/a' => 'Divorciado/a', 'Acompañado/a' => 'Acompañado/a'),
                    'attr'=>array(
                    'class'=>'form-control input-sm estadocivilPaciente'
                    )))
            ->add('sexo','choice',array('label' => 'Sexo','required' => false,'empty_value'=>'Seleccione sexo...',
                    'choices'  => array('M' => 'Masculino', 'F' => 'Femenino'),
                    'attr'=>array(
                    'class'=>'form-control input-sm sexoPaciente'
                    )))
            
            ->add('ocupacion','text',array('label' => 'Ocupación','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm ocupacionPaciente'
                    )))
            ->add('lugarTrabajo','text',array('label' => 'Lugar de Trabajo','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm lugartrabajoPaciente'
                    )))
            ->add('fechaNacimiento', null,
                  array('label'  => 'Fecha nacimiento','required'=>false,
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-md calZebra'),
                        'format' => 'dd-MM-yyyy',
                       ))
            ->add('referidoPor','text',array('label' => 'Referido por','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm referidoporPaciente'
                    )))
            ->add('personaEmergencia','text',array('label' => 'En caso de Emergencia llamar a','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm emergenciaPaciente'
                    )))
            ->add('telefonoEmergencia','text',array('label' => 'Al telefono','required' => false,
                    'attr'=>array(
                    'class'=>'form-control input-sm telefonoemerPaciente'
                    )))
//             ->add('enteradoPor','text',array('label' => 'Enterado por','required' => false,
//                    'attr'=>array(
//                    'class'=>'form-control input-sm'
//                    )))
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
