<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\SesionVentaTratamiento;
use DGPlusbelleBundle\Entity\SeguimientoTratamiento;
use DGPlusbelleBundle\Entity\HistorialConsulta;
use DGPlusbelleBundle\Entity\ImagenTratamiento;
use DGPlusbelleBundle\Form\SesionVentaTratamientoType;

/**
 * SesionVentaTratamiento controller.
 *
 * @Route("/admin/sesionventatratamiento")
 */
class SesionVentaTratamientoController extends Controller
{

    /**
     * Lists all SesionVentaTratamiento entities.
     *
     * @Route("/", name="admin_sesionventatratamiento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:SesionVentaTratamiento')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SesionVentaTratamiento entity.
     *
     * @Route("/create/{id}", name="admin_sesionventatratamiento_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:SesionVentaTratamiento:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $entity = new SesionVentaTratamiento();
        $seguimiento1 = new ImagenTratamiento();
        $form = $this->createCreateForm($entity, $id);
        $form->handleRequest($request);
        $entity->setFechaSesion(new \DateTime('now'));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $id2=$entity->getId();
            //die();
            $entity2 =  $em->getRepository('DGPlusbelleBundle:SesionVentaTratamiento')->find($id2);
            $seguimiento1->setSesionVentaTratamiento($entity2);
            
            
      /*      
         if($entity->getFileAntes()!=null){
                $path = $this->container->getParameter('photo.paciente');

                $fecha = date('Y-m-d His');
                $extension = $entity->getFileAntes()->getClientOriginalExtension();
                $nombreArchivo = $entity->getId()."-"."Antes"."-".$fecha.".".$extension;
                $em->persist($entity);
                $em->flush();
                //var_dump($path.$nombreArchivo);

                $seguimiento1->setFotoAntes($nombreArchivo);
                $entity->getFileAntes()->move($path,$nombreArchivo);
                $em->persist($seguimiento1);
                $em->flush();
            }  
            
             if($entity->getFileDespues()!=null){
                $path = $this->container->getParameter('photo.paciente');

                $fecha = date('Y-m-d His');
                $extension = $entity->getFileDespues()->getClientOriginalExtension();
                $nombreArchivo = $entity->getId()."-"."Despues"."-".$fecha.".".$extension;
                $em->persist($entity);
                $em->flush();
                //var_dump($path.$nombreArchivo);

                $seguimiento1->setFotoDespues($nombreArchivo);  
                $entity->getFileDespues()->move($path,$nombreArchivo);
                $em->persist($seguimiento1);
                $em->flush();
            } */
            
              foreach($entity->getPlacas() as $row){
                //var_dump($row->getFileAntes());
              //var_dump($entity->getPlacas());
             // die();  
            if($row->getFileAntes()!=null){
                $path = $this->container->getParameter('photo.paciente');

                $fecha = date('Y-m-d His');
                $extension = $row->getFileAntes()->getClientOriginalExtension();
                $nombreArchivo = $row->getId()."-"."Antes"."-".$fecha.".".$extension;
                
                //$seguimiento->setFotoAntes($nombreArchivo);
              
                $row->setFotoAntes($nombreArchivo);
                $row->getFileAntes()->move($path,$nombreArchivo);
                //$em->merge($seguimiento);
               // $em->merge($seguimiento1);
                $em->persist($row);
                $em->flush();
                
            }  
           }
           
           
          
            
            
            
            
            $parameters = $request->request->all();
            $recetaid = $parameters['dgplusbellebundle_sesionventatratamiento']['sesiontratamiento'];
            var_dump($parameters);
            //die();
            
            $dql = "SELECT det.id, det.nombre "
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
                $dataReporte2->setSesionVentaTratamientoReceta($entity);
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
            }   
            
            
            
            
            $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findOneBy(array('idPersonaTratamiento' => $id, 
                                                                                         ));
            $seguimiento->setNumSesion($seguimiento->getNumSesion() + 1);
            $em->merge($seguimiento);
            $em->flush();

            $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->findOneBy(array('persona' => $entity->getPersonaTratamiento ()->getPaciente()->getId()));
              
            return $this->redirect($this->generateUrl('admin_historial_consulta', array('id' => 'P'.$paciente->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SesionVentaTratamiento entity.
     *
     * @param SesionVentaTratamiento $entity The entity
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SesionVentaTratamiento $entity, $id)
    {
        $form = $this->createForm(new SesionVentaTratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_sesionventatratamiento_create', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit','submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')
        ));

        return $form;
    }

    /**
     * Displays a form to create a new SesionVentaTratamiento entity.
     *
     * @Route("/new/{id}", name="admin_sesionventatratamiento_new", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new SesionVentaTratamiento();
        $form   = $this->createCreateForm($entity, $id);

        $em = $this->getDoctrine()->getManager();
        $personaTratamiento = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($id);
        
        $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findBy(array('idPersonaTratamiento' => $id));
        
        return array(
            'entity' => $entity,
            'personaTratamiento' => $personaTratamiento,
            'seguimiento' => $seguimiento,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SesionVentaTratamiento entity.
     *
     * @Route("/{id}", name="admin_sesionventatratamiento_show", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:SesionVentaTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SesionVentaTratamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SesionVentaTratamiento entity.
     *
     * @Route("/{id}/edit", name="admin_sesionventatratamiento_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:SesionVentaTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SesionVentaTratamiento entity.');
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
    * Creates a form to edit a SesionVentaTratamiento entity.
    *
    * @param SesionVentaTratamiento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SesionVentaTratamiento $entity)
    {
        $form = $this->createForm(new SesionVentaTratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_sesionventatratamiento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SesionVentaTratamiento entity.
     *
     * @Route("/{id}", name="admin_sesionventatratamiento_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:SesionVentaTratamiento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:SesionVentaTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SesionVentaTratamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_sesionventatratamiento_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SesionVentaTratamiento entity.
     *
     * @Route("/{id}", name="admin_sesionventatratamiento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:SesionVentaTratamiento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SesionVentaTratamiento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_sesionventatratamiento'));
    }

    /**
     * Creates a form to delete a SesionVentaTratamiento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_sesionventatratamiento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/sesiones/data/listado/tratamiento", name="admin_sesiones_paciente_data_tratamiento")
     */
    public function dataSesionesTratamientoAction(Request $request)
    {
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        $id = $request->query->get('id');
        
        $em = $this->getDoctrine()->getEntityManager();
        $expedientesTotal = $em->getRepository('DGPlusbelleBundle:SesionVentaTratamiento')->findBy(array('personaTratamiento'=>$id));
        
        $paciente['draw']=$draw++;  
        $paciente['recordsTotal'] = count($expedientesTotal);
        $paciente['recordsFiltered']= count($expedientesTotal);
        $paciente['data']= array();
        
        $arrayFiltro = explode(' ',$busqueda['value']);
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        
        $dql = "SELECT suc.nombre as sucursal, DATE_FORMAT(ab.fechaSesion,'%d-%m-%Y') as fechaSesion, CONCAT(per.nombres,' ', per.apellidos) as empleado FROM DGPlusbelleBundle:SesionVentaTratamiento ab "
                . "JOIN ab.empleado emp "
                . "JOIN emp.persona per "
                . "JOIN ab.sucursal suc "
                . "WHERE ab.personaTratamiento=:id "
                . "ORDER BY ab.fechaSesion DESC ";
        $paciente['data'] = $em->createQuery($dql)
                ->setParameter('id',$id)
                ->setFirstResult($start)
                ->setMaxResults($longitud)
                ->getResult();
        
        var_dump($id);
        
        return new Response(json_encode($paciente));
    }
}
