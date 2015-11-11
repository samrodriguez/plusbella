<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\DetallePlantilla;
use DGPlusbelleBundle\Form\DetallePlantillaType;

/**
 * DetallePlantilla controller.
 *
 * @Route("/admin/detalleplantilla")
 */
class DetallePlantillaController extends Controller
{

    /**
     * Lists all DetallePlantilla entities.
     *
     * @Route("/", name="admin_detalleplantilla")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:DetallePlantilla')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new DetallePlantilla entity.
     *
     * @Route("/", name="admin_detalleplantilla_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:DetallePlantilla:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new DetallePlantilla();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_detalleplantilla_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a DetallePlantilla entity.
     *
     * @param DetallePlantilla $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(DetallePlantilla $entity)
    {
        $form = $this->createForm(new DetallePlantillaType(), $entity, array(
            'action' => $this->generateUrl('admin_detalleplantilla_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new DetallePlantilla entity.
     *
     * @Route("/new", name="admin_detalleplantilla_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new DetallePlantilla();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a DetallePlantilla entity.
     *
     * @Route("/{id}", name="admin_detalleplantilla_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:DetallePlantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DetallePlantilla entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing DetallePlantilla entity.
     *
     * @Route("/{id}/edit", name="admin_detalleplantilla_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:DetallePlantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DetallePlantilla entity.');
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
    * Creates a form to edit a DetallePlantilla entity.
    *
    * @param DetallePlantilla $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(DetallePlantilla $entity)
    {
        $form = $this->createForm(new DetallePlantillaType(), $entity, array(
            'action' => $this->generateUrl('admin_detalleplantilla_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing DetallePlantilla entity.
     *
     * @Route("/{id}", name="admin_detalleplantilla_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:DetallePlantilla:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:DetallePlantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DetallePlantilla entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_detalleplantilla_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a DetallePlantilla entity.
     *
     * @Route("/{id}", name="admin_detalleplantilla_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:DetallePlantilla')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DetallePlantilla entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_detalleplantilla'));
    }

    /**
     * Creates a form to delete a DetallePlantilla entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_detalleplantilla_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
