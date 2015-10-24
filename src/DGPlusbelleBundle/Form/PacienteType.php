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
         
                
            ->add('dui')
            ->add('estadoCivil','choice',array(
                    'choices'  => array('s' => 'Soltero', 'c' => 'Casado', 'e' => 'Estudiante'),))
    ->add('sexo','choice',array(
                    'choices'  => array('m' => 'Masculino', 'f' => 'Femenino'),
            ))
            ->add('ocupacion')
            ->add('lugarTrabajo')
            ->add('fechaNacimiento')
            ->add('referidoPor')
            ->add('personaEmergencia')
            ->add('telefonoEmergencia')
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
