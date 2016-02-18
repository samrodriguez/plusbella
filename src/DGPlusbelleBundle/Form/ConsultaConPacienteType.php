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
            /*->add('fechaConsulta', null,
                  array('label'  => 'Fecha consulta','required'=>false,
                        'widget' => 'single_text',
                        'attr'   => array('class' => 'form-control input-sm fechaConsulta'),
                       ))*/
            ->add('horaInicio','time',array('label' => 'Hora Inicio',
                    'attr'=>array(
                    'class'=>'horaInicioConsulta '
                    )))
            ->add('horaFin','time',array('label' => 'Hora Fin',
                    'attr'=>array(
                    'class'=>'horaFinConsulta '
                    )))
            ->add('observacion','textarea',array('label' => 'Observación','required'=>false,
                    'attr'=>array(
                    'class'=>'form-control input-sm observacionConsulta'
                    )))
//            ->add('incapacidad', 'choice', array(
//                    'choices'  => array('1' => 'Sí', '0' => 'No'),
//                    'multiple' => false,
//                    'expanded'=>'true',
//                    'preferred_choices' => array(0),  
//                    'data'=>0
//                ))
            //->add('cita')
            ->add('paciente','entity', array( 'label' => 'Paciente','required'=>false,
                         'empty_value'   => 'Seleccione un paciente...',
                         'class'         => 'DGPlusbelleBundle:Paciente',
                         'attr'=>array(
                         'class'=>'form-control input-sm pacienteConsulta'
                         )
                       ))
            ->add('tipoConsulta','entity', array( 'label' => 'Tipo de consulta','required'=>false,
                         'empty_value'   => 'Seleccione un tipo de consulta...',
                         'class'         => 'DGPlusbelleBundle:TipoConsulta',
            'query_builder' => function(EntityRepository $repository) {
                return $repository->obtenerTipoConsActivo();
            },
                         
                         'attr'=>array(
                         'class'=>'form-control tipoConsulta'
                         )
                       ))
                
            ->add('reportePlantilla', 'choice', array(
                    'label'=> 'Registro clínico',
                    'choices'  => array('1' => 'Sí', '0' => 'No'),
                    'multiple' => false,
                    'expanded'=>'true'
                   
                 
                ))
                    
                    
                    ->add('registraReceta', 'choice', array(
                    'label'=> 'Receta',
                    'choices'  => array('1' => 'Sí', '0' => 'No'),
                    'multiple' => false,
                    'expanded'=>'true'
                   
                 
                ))
            ->add('costoConsulta', 'text', array('required'=>false,
                    'attr'=>array(
                         'class'=>'form-control costoConsulta'
                         )
                       ))
            ->add('plantilla', 'entity', array('required'=>false,
                    'label'         =>  'Historias clínicas',
                    'empty_value'=>'Seleccione una opcion',
                    'class'         =>  'DGPlusbelleBundle:Plantilla',
                    'query_builder' => function(EntityRepository $repository) {
                    return $repository->otrosDocActivo();
                },
                    'mapped' => false
                ))   
                    
                    ->add('sesiontratamiento', 'entity', array('required'=>false,
                    'label'         =>  'Nombre',
                    'empty_value'=>'Seleccione una opcion',
                    'class'         =>  'DGPlusbelleBundle:Plantilla',
                    'query_builder' => function(EntityRepository $repository) {
                return $repository->obtenerRecetasActivo();
            },
                    'mapped' => false
                ))   
          /*  ->add('campos', 'choice', array(
                    'multiple'  => true,
                    'expanded'  => false,
                    'attr' => array('style' => 'height:150px'),
                    'mapped'    => false,
                    'required' => false
                )) */
                    
            ->add('tratamiento','entity', array( 'label' => 'Tratamiento','required'=>false,
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:Tratamiento',
            'query_builder' => function(EntityRepository $repository) {
                return $repository->obtenerTratActivo();
            },
                         
                         'attr'=>array(
                         'class'=>'form-control input-sm tratamientoConsulta'
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
                    
                    
            ->add('placas2','collection',array(
                'type' => new ImagenConsultaType(),
                'label'=>' ',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                ))    
                    
            ->add('sucursal','entity',array('label' => 'Seleccione sucursal...','required'=>false,
                'class'=>'DGPlusbelleBundle:Sucursal',
                'empty_value' => 'Seleccione una sucursal...',
                'query_builder' => function(EntityRepository $repository) {
                  return $repository->obtenerSucActivo();
                },
                'property'=>'nombre',
                'multiple'=>false,
                'expanded'=>false,
                    'attr'=>array(
                    'class'=>'sucursalPaquete'
                    ))) 
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
