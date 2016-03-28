<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\DetalleEstetica;
use DGPlusbelleBundle\Form\DetalleEsteticaType;

/**
 * DetalleEstetica controller.
 *
 * @Route("/admin/detalle-estetica")
 */
class DetalleEsteticaController extends Controller
{

    /**
     * Lists all DetalleEstetica entities.
     *
     * @Route("/", name="admin_detalle-estetica")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:DetalleEstetica')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new DetalleEstetica entity.
     *
     * @Route("/", name="admin_detalle-estetica_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:DetalleEstetica:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new DetalleEstetica();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_detalle-estetica_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a DetalleEstetica entity.
     *
     * @param DetalleEstetica $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(DetalleEstetica $entity)
    {
        $form = $this->createForm(new DetalleEsteticaType(), $entity, array(
            'action' => $this->generateUrl('admin_detalle-estetica_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new DetalleEstetica entity.
     *
     * @Route("/new", name="admin_detalle-estetica_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new DetalleEstetica();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a DetalleEstetica entity.
     *
     * @Route("/{id}", name="admin_detalle-estetica_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:DetalleEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DetalleEstetica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing DetalleEstetica entity.
     *
     * @Route("/{id}/edit", name="admin_detalle-estetica_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:DetalleEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DetalleEstetica entity.');
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
    * Creates a form to edit a DetalleEstetica entity.
    *
    * @param DetalleEstetica $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(DetalleEstetica $entity)
    {
        $form = $this->createForm(new DetalleEsteticaType(), $entity, array(
            'action' => $this->generateUrl('admin_detalle-estetica_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing DetalleEstetica entity.
     *
     * @Route("/{id}", name="admin_detalle-estetica_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:DetalleEstetica:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:DetalleEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DetalleEstetica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_detalle-estetica_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a DetalleEstetica entity.
     *
     * @Route("/{id}", name="admin_detalle-estetica_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:DetalleEstetica')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DetalleEstetica entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_detalle-estetica'));
    }

    /**
     * Creates a form to delete a DetalleEstetica entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_detalle-estetica_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
