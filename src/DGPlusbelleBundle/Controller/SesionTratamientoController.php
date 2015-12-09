<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\SesionTratamiento;
use DGPlusbelleBundle\Entity\SeguimientoPaquete;
use DGPlusbelleBundle\Form\SesionTratamientoType;

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
     * @Route("/", name="admin_sesiontratamiento_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:SesionTratamiento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SesionTratamiento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $entity->setFechaSesion(new \DateTime('now'));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $seguimiento = new SeguimientoPaquete();
            
         if($entity->getFileAntes()!=null){
                $path = $this->container->getParameter('photo.paciente');

                $fecha = date('Y-m-d His');
                $extension = $entity->getFileAntes()->getClientOriginalExtension();
                $nombreArchivo = $entity->getId()."-"."Antes"."-".$fecha.".".$extension;
                $em->persist($entity);
                $em->flush();
                //var_dump($path.$nombreArchivo);

                
                //$seguimiento = new SeguimientoPaquete;
                $seguimiento->setFotoAntes($nombreArchivo);
                $entity->getFileAntes()->move($path,$nombreArchivo);
                $em->persist($seguimiento);
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

                
                //$seguimiento = new SeguimientoPaquete;
                $seguimiento->setFotoDespues($nombreArchivo);               
                $entity->getFileDespues()->move($path,$nombreArchivo);
                $em->persist($seguimiento);
                $em->flush();
            } 
            
              

            return $this->redirect($this->generateUrl('admin_historialconsulta_new', array('id' => $entity->getId())));
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
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SesionTratamiento $entity)
    {
        $form = $this->createForm(new SesionTratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_sesiontratamiento_create'),
            'method' => 'POST',
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
        $form   = $this->createCreateForm($entity);
        
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