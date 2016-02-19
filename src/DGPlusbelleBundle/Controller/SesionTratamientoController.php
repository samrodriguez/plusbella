<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\SesionTratamiento;
use DGPlusbelleBundle\Entity\ImagenTratamiento;
use DGPlusbelleBundle\Entity\HistorialConsulta;
use DGPlusbelleBundle\Entity\SeguimientoPaquete;
use DGPlusbelleBundle\Form\SesionTratamientoType;
use Doctrine\ORM\EntityRepository;

/**
 * SesionTratamiento controller.
 *
 * @Route("/admin/sesiontratamiento")
 */
class SesionTratamientoController extends Controller
{

    /**
     * Lists all SesionTratamiento entities.
     *
     * @Route("/", name="admin_sesiontratamiento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:SesionTratamiento')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SesionTratamiento entity.
     *
     * @Route("/create/{id}", name="admin_sesiontratamiento_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:SesionTratamiento:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $entity = new SesionTratamiento();
        $seguimiento1 = new ImagenTratamiento();
        $form = $this->createCreateForm($entity, $id);
        $form->handleRequest($request);
        $entity->setFechaSesion(new \DateTime('now'));

        $em = $this->getDoctrine()->getManager();
        $ventaPaquete = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);
        $entity->setVentaPaquete($ventaPaquete);
       
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $entity->setRegistraReceta("0");
            $em->persist($entity);
            $em->flush();
            
            $id2=$entity->getId();
            //die();
            $entity2 =  $em->getRepository('DGPlusbelleBundle:SesionTratamiento')->find($id2);
           // $seguimiento1->setSesionTratamiento($entity);
            
            
           
            foreach($entity->getPlacas() as $row){
            
                if($row->getFileAntes()!=null){
                    $path = $this->container->getParameter('photo.paciente');

                    $fecha = date('Y-m-d His');
                    $extension = $row->getFileAntes()->getClientOriginalExtension();
                    $nombreArchivo = $row->getId()."-"."Antes"."-".$fecha.".".$extension;

                    $row->setFotoAntes($nombreArchivo);
                    $row->getFileAntes()->move($path,$nombreArchivo);

                    $em->persist($row);
                    $em->flush();

                }  
           } 
           
            $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findOneBy(array('idVentaPaquete' => $id, 
                                                                                                    'tratamiento' => $entity->getTratamiento()->getId() 
                                                                                                    ));

            
            $tratamientos = $em->getRepository('DGPlusbelleBundle:PaqueteTratamiento')->findBy(array('paquete' => $ventaPaquete->getPaquete()->getId()));
            $aux = 0;
            $total = count($tratamientos);
            foreach ($tratamientos as $trat){
                 if($seguimiento->getNumSesion() + 1 >= $trat->getNumSesiones()){
                     $aux++;
                }
            }
            
            if($aux < $total){
                $ventaPaquete->setEstado(2);
            } else {
                $ventaPaquete->setEstado(3);
            }
            $parameters = $request->request->all();
            //$recetaid = $parameters['dgplusbellebundle_sesiontratamiento']['sesiontratamiento'];
            //var_dump($parameters);
            //die();
            
            /*$dql = "SELECT det.id, det.nombre "
                    . "FROM DGPlusbelleBundle:DetallePlantilla det "
                    . "JOIN det.plantilla pla "
                    . "WHERE pla.id =  :plantillaid";
            
            $parametros2 = $em->createQuery($dql)
                        ->setParameter('plantillaid', $recetaid)
                        ->getResult();
            
            foreach($parametros2 as $p){
                $dataReporte2 = new HistorialConsulta;
                $detalle = $em->getRepository('DGPlusbelleBundle:DetallePlantilla')->find($p['id']);
                
                $dataReporte2->setDetallePlantilla($detalle);       
                $dataReporte2->setSesionTratamientoReceta($entity);
                $dataReporte2->setConsulta(null);
                
                $nparam = explode(" ", $p['nombre']);
                //var_dump(count($nparam)); 
                $lon = count($nparam);
                if($lon > 1){
                    $pnombre = $nparam[0];
                    foreach($nparam as $key => $par){
                        //var_dump($key);
                        if($key +1 != $lon){
                            //var_dump($lon);
                            $pnombre .= '_'.$nparam[$key + 1];
                        }
                    }
                    $dataReporte2->setValorDetalle($parameters[$pnombre."2"]);
                } else {
                    $dataReporte2->setValorDetalle($parameters[$p['nombre']."2"]);
                }
               //var_dump($p['nombre']); 
                
                
                $em->persist($dataReporte2);
                $em->flush();
            }   */
            
            
            $em->merge($ventaPaquete);
            $em->flush();
            
            
            $seguimiento->setNumSesion($seguimiento->getNumSesion() + 1);
            $em->merge($seguimiento);
            $em->flush();
           
            $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->findOneBy(array('persona' => $entity->getVentaPaquete()->getPaciente()->getId()));
              
            return $this->redirect($this->generateUrl('admin_historial_consulta', array('id' => 'P'.$paciente->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SesionTratamiento entity.
     *
     * @param SesionTratamiento $entity The entity
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SesionTratamiento $entity, $id)
    {
        $form = $this->createForm(new SesionTratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_sesiontratamiento_create', array('id' => $id)),
            'method' => 'POST',
        ));

        $em = $this->getDoctrine()->getManager();
        $idtratamientos=array();    
        
        $venta = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);
        $tratamientos = $em->getRepository('DGPlusbelleBundle:PaqueteTratamiento')->findBy(array('paquete' => $venta->getPaquete()->getId()));
        
        foreach ($tratamientos as $trat){
            $idtrat = $trat->getTratamiento()->getId();
            $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findOneBy(array('tratamiento' => $idtrat,
                                                                                                    'idVentaPaquete' => $id
                                                                                                ));
            if($seguimiento->getNumSesion() < $trat->getNumSesiones()){
                array_push($idtratamientos, $idtrat); 
            }
            
        }
        
        $form->add('tratamiento', 'entity', 
                  array( 'label'         => 'Tratamiento','required'=>false,
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:Tratamiento',
                         'query_builder' => function(EntityRepository $r) use ( $idtratamientos ){
                                                return $r->createQueryBuilder('s')
                                                        ->select('s')
                                                        //->innerJoin('s.tratamiento', 'p')
                                                        ->where('s.id IN (:tratamientos)')
                                                        ->setParameter('tratamientos', $idtratamientos)
                                                    ;   
                                            } ,
                         'attr'=>array(
                         'class'=>'form-control input-sm'
                         )
                    ));  
        
        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')
            
            ));

        return $form;
    }

    /**
     * Displays a form to create a new SesionTratamiento entity.
     *
     * @Route("/new/{id}", name="admin_sesiontratamiento_new", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new SesionTratamiento();
        $form   = $this->createCreateForm($entity, $id);
        
        $em = $this->getDoctrine()->getManager();
        $ventaPaquete = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);
        
        $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findBy(array('idVentaPaquete' => $id));

        return array(
            'entity' => $entity,
            'ventaPaquete' => $ventaPaquete,
            'seguimiento' => $seguimiento,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SesionTratamiento entity.
     *
     * @Route("/{id}", name="admin_sesiontratamiento_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:SesionTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SesionTratamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SesionTratamiento entity.
     *
     * @Route("/{id}/edit", name="admin_sesiontratamiento_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:SesionTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SesionTratamiento entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a SesionTratamiento entity.
    *
    * @param SesionTratamiento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SesionTratamiento $entity)
    {
        $form = $this->createForm(new SesionTratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_sesiontratamiento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SesionTratamiento entity.
     *
     * @Route("/{id}", name="admin_sesiontratamiento_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:SesionTratamiento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:SesionTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SesionTratamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_sesiontratamiento_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SesionTratamiento entity.
     *
     * @Route("/{id}", name="admin_sesiontratamiento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:SesionTratamiento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SesionTratamiento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_sesiontratamiento'));
    }

    /**
     * Creates a form to delete a SesionTratamiento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_sesiontratamiento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
