<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DGPlusbelleBundle\Entity\TipoConsulta;
use DGPlusbelleBundle\Entity\Tratamiento;
use Doctrine\ORM\EntityRepository;

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
                  array('label'  => 'Fecha consulta','required'=>false,
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
            ->add('observacion','textarea',array('label' => 'Observación','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm'
                    )))
            ->add('incapacidad', 'choice', array(
                    'choices'  => array('1' => 'Sí', '0' => 'No'),
                    'multiple' => false,
                    'expanded'=>'true'
                  
                    
                    
                
                ))
            //->add('cita')
            ->add('paciente','entity', array( 'label' => 'Paciente',
                         'empty_value'   => 'Seleccione un paciente...',
                         'class'         => 'DGPlusbelleBundle:Paciente',
                         'attr'=>array(
                         'class'=>'form-control input-sm'
                         )
                       ))
            ->add('tipoConsulta','entity', array( 'label' => 'Tipo de consulta','required'=>false,
                         'empty_value'   => 'Seleccione un tipo de consulta...',
                         'class'         => 'DGPlusbelleBundle:TipoConsulta',
            'query_builder' => function(EntityRepository $repository) {
                return $repository->obtenerTipoConsActivo();
            },
                         
                         'attr'=>array(
                         'class'=>'form-control'
                         )
                       ))
                
            ->add('reportePlantilla', 'choice', array(
                    'label'=> 'Requiere plantilla',
                    'choices'  => array('1' => 'Sí', '0' => 'No'),
                    'multiple' => false,
                    'expanded'=>'true'
                   
                  
                ))
            ->add('tratamiento','entity', array( 'label' => 'Tratamiento','required'=>false,
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:Tratamiento',
            'query_builder' => function(EntityRepository $repository) {
                return $repository->obtenerTratActivo();
            },
                         
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
