<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\OpcionesDetalleEstetica;
use DGPlusbelleBundle\Form\OpcionesDetalleEsteticaType;

/**
 * OpcionesDetalleEstetica controller.
 *
 * @Route("/admin/opcionesdetalleestetica")
 */
class OpcionesDetalleEsteticaController extends Controller
{

    /**
     * Lists all OpcionesDetalleEstetica entities.
     *
     * @Route("/", name="admin_opcionesdetalleestetica")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:OpcionesDetalleEstetica')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new OpcionesDetalleEstetica entity.
     *
     * @Route("/", name="admin_opcionesdetalleestetica_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:OpcionesDetalleEstetica:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new OpcionesDetalleEstetica();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_opcionesdetalleestetica_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a OpcionesDetalleEstetica entity.
     *
     * @param OpcionesDetalleEstetica $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(OpcionesDetalleEstetica $entity)
    {
        $form = $this->createForm(new OpcionesDetalleEsteticaType(), $entity, array(
            'action' => $this->generateUrl('admin_opcionesdetalleestetica_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new OpcionesDetalleEstetica entity.
     *
     * @Route("/new", name="admin_opcionesdetalleestetica_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OpcionesDetalleEstetica();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a OpcionesDetalleEstetica entity.
     *
     * @Route("/{id}", name="admin_opcionesdetalleestetica_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:OpcionesDetalleEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OpcionesDetalleEstetica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing OpcionesDetalleEstetica entity.
     *
     * @Route("/{id}/edit", name="admin_opcionesdetalleestetica_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:OpcionesDetalleEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OpcionesDetalleEstetica entity.');
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
    * Creates a form to edit a OpcionesDetalleEstetica entity.
    *
    * @param OpcionesDetalleEstetica $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(OpcionesDetalleEstetica $entity)
    {
        $form = $this->createForm(new OpcionesDetalleEsteticaType(), $entity, array(
            'action' => $this->generateUrl('admin_opcionesdetalleestetica_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing OpcionesDetalleEstetica entity.
     *
     * @Route("/{id}", name="admin_opcionesdetalleestetica_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:OpcionesDetalleEstetica:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:OpcionesDetalleEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OpcionesDetalleEstetica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_opcionesdetalleestetica_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a OpcionesDetalleEstetica entity.
     *
     * @Route("/{id}", name="admin_opcionesdetalleestetica_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:OpcionesDetalleEstetica')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OpcionesDetalleEstetica entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_opcionesdetalleestetica'));
    }

    /**
     * Creates a form to delete a OpcionesDetalleEstetica entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_opcionesdetalleestetica_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
