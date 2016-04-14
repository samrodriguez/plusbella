<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\ConsultaBotox;
use DGPlusbelleBundle\Form\ConsultaBotoxType;

/**
 * ConsultaBotox controller.
 *
 * @Route("/admin/consulta-botox")
 */
class ConsultaBotoxController extends Controller
{

    /**
     * Lists all ConsultaBotox entities.
     *
     * @Route("/", name="admin_consulta-botox")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:ConsultaBotox')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ConsultaBotox entity.
     *
     * @Route("/", name="admin_consulta-botox_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:ConsultaBotox:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ConsultaBotox();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_consulta-botox_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ConsultaBotox entity.
     *
     * @param ConsultaBotox $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ConsultaBotox $entity)
    {
        $form = $this->createForm(new ConsultaBotoxType(), $entity, array(
            'action' => $this->generateUrl('admin_consulta-botox_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ConsultaBotox entity.
     *
     * @Route("/new", name="admin_consulta-botox_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ConsultaBotox();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ConsultaBotox entity.
     *
     * @Route("/{id}", name="admin_consulta-botox_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ConsultaBotox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConsultaBotox entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ConsultaBotox entity.
     *
     * @Route("/{id}/edit", name="admin_consulta-botox_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ConsultaBotox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConsultaBotox entity.');
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
    * Creates a form to edit a ConsultaBotox entity.
    *
    * @param ConsultaBotox $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ConsultaBotox $entity)
    {
        $form = $this->createForm(new ConsultaBotoxType(), $entity, array(
            'action' => $this->generateUrl('admin_consulta-botox_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ConsultaBotox entity.
     *
     * @Route("/{id}", name="admin_consulta-botox_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:ConsultaBotox:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ConsultaBotox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConsultaBotox entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_consulta-botox_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ConsultaBotox entity.
     *
     * @Route("/{id}", name="admin_consulta-botox_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:ConsultaBotox')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ConsultaBotox entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_consulta-botox'));
    }

    /**
     * Creates a form to delete a ConsultaBotox entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_consulta-botox_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
