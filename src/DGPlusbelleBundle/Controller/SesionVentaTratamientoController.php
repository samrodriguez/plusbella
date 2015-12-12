<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\SesionVentaTratamiento;
use DGPlusbelleBundle\Entity\SeguimientoTratamiento;
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
}
