<?php

namespace DGPlusbelleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SesionTratamientoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('fechaSesion')
            //->add('horaInicio')
            //->add('horaFin')
            //->add('ventaPaquete')
            ->add('sucursal','entity',array(
                'label' => 'Sucursal',
                'empty_value'   => 'Seleccione sucursal...',      
                'required'=>false,
                'class'=>'DGPlusbelleBundle:Sucursal',
                'query_builder' => function(EntityRepository $repository) {
                  return $repository->obtenerSucActivo();
                },
                'property'=>'nombre',
                    'attr'=>array(
                    'class'=>'sucursalPaquete'
                    )))       
            //->add('paciente')
            ->add('empleado',null,array('label' => 'Empleado','required'=>false,
                'empty_value'   => 'Seleccione empleado...',      
                'attr'=>array(
                'class'=>'form-control input-sm sesionEmpleado'
                    ))) 
                  
                       
              
                        
                        
                        
//            ->add('sesiontratamiento', 'entity', array('required'=>false,
//                    'label'         =>  'Nombre médica',
//                    'empty_value'=>'Seleccione una opcion',
//                    'class'         =>  'DGPlusbelleBundle:Plantilla',
//                    'mapped' => false
//                ))  
//                        
//                        
//            ->add('registraReceta', 'choice', array(
//                    'label'=> 'Requiere receta',
//                    'choices'  => array('1' => 'Sí', '0' => 'No'),
//                    'multiple' => false,
//                    'expanded'=>'true'
//                   
//                 
//                ))
            //->add('tratamiento')
           /* ->add('fotoAntes',null, array(
                    'label'         =>  'Foto Antes',                               
                    'required'  => false,
                    'mapped' => false
                )) */
          /*  ->add('fileAntes',null, array('label'=>'Foto antes','required'=>false,
                    'attr'=>array('class'=>'fotoAntes'  
                        
                    )))  
            ->add('fileDespues',null, array('label'=>'Foto despues','required'=>false,
                    'attr'=>array('class'=>'fotoDespues' 
                    )))   
                */
             ->add('placas','collection',array(
                'type' => new ImagenTratamientoType(),
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
            'data_class' => 'DGPlusbelleBundle\Entity\SesionTratamiento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dgplusbellebundle_sesiontratamiento';
    }
}
