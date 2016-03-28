<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\ComposicionCorporal;
use DGPlusbelleBundle\Form\ComposicionCorporalType;

/**
 * ComposicionCorporal controller.
 *
 * @Route("/admin/composicioncorporal")
 */
class ComposicionCorporalController extends Controller
{

    /**
     * Lists all ComposicionCorporal entities.
     *
     * @Route("/", name="admin_composicioncorporal")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:ComposicionCorporal')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ComposicionCorporal entity.
     *
     * @Route("/", name="admin_composicioncorporal_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:ComposicionCorporal:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ComposicionCorporal();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_composicioncorporal_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ComposicionCorporal entity.
     *
     * @param ComposicionCorporal $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ComposicionCorporal $entity)
    {
        $form = $this->createForm(new ComposicionCorporalType(), $entity, array(
            'action' => $this->generateUrl('admin_composicioncorporal_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ComposicionCorporal entity.
     *
     * @Route("/new", name="admin_composicioncorporal_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ComposicionCorporal();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ComposicionCorporal entity.
     *
     * @Route("/{id}", name="admin_composicioncorporal_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ComposicionCorporal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComposicionCorporal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ComposicionCorporal entity.
     *
     * @Route("/{id}/edit", name="admin_composicioncorporal_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ComposicionCorporal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComposicionCorporal entity.');
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
    * Creates a form to edit a ComposicionCorporal entity.
    *
    * @param ComposicionCorporal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ComposicionCorporal $entity)
    {
        $form = $this->createForm(new ComposicionCorporalType(), $entity, array(
            'action' => $this->generateUrl('admin_composicioncorporal_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ComposicionCorporal entity.
     *
     * @Route("/{id}", name="admin_composicioncorporal_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:ComposicionCorporal:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ComposicionCorporal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComposicionCorporal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_composicioncorporal_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ComposicionCorporal entity.
     *
     * @Route("/{id}", name="admin_composicioncorporal_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:ComposicionCorporal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ComposicionCorporal entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_composicioncorporal'));
    }

    /**
     * Creates a form to delete a ComposicionCorporal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_composicioncorporal_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
