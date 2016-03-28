<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\HistorialEstetica;
use DGPlusbelleBundle\Form\HistorialEsteticaType;

/**
 * HistorialEstetica controller.
 *
 * @Route("/admin/historial-estetica")
 */
class HistorialEsteticaController extends Controller
{

    /**
     * Lists all HistorialEstetica entities.
     *
     * @Route("/", name="admin_historial-estetica")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:HistorialEstetica')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new HistorialEstetica entity.
     *
     * @Route("/", name="admin_historial-estetica_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:HistorialEstetica:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new HistorialEstetica();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_historial-estetica_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a HistorialEstetica entity.
     *
     * @param HistorialEstetica $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(HistorialEstetica $entity)
    {
        $form = $this->createForm(new HistorialEsteticaType(), $entity, array(
            'action' => $this->generateUrl('admin_historial-estetica_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new HistorialEstetica entity.
     *
     * @Route("/new", name="admin_historial-estetica_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new HistorialEstetica();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a HistorialEstetica entity.
     *
     * @Route("/{id}", name="admin_historial-estetica_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:HistorialEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HistorialEstetica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing HistorialEstetica entity.
     *
     * @Route("/{id}/edit", name="admin_historial-estetica_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:HistorialEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HistorialEstetica entity.');
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
    * Creates a form to edit a HistorialEstetica entity.
    *
    * @param HistorialEstetica $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(HistorialEstetica $entity)
    {
        $form = $this->createForm(new HistorialEsteticaType(), $entity, array(
            'action' => $this->generateUrl('admin_historial-estetica_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing HistorialEstetica entity.
     *
     * @Route("/{id}", name="admin_historial-estetica_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:HistorialEstetica:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:HistorialEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HistorialEstetica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_historial-estetica_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a HistorialEstetica entity.
     *
     * @Route("/{id}", name="admin_historial-estetica_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:HistorialEstetica')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find HistorialEstetica entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_historial-estetica'));
    }

    /**
     * Creates a form to delete a HistorialEstetica entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_historial-estetica_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
