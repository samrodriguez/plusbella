<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Comision;
use DGPlusbelleBundle\Form\ComisionType;

/**
 * Comision controller.
 *
 * @Route("/admin/comision")
 */
class ComisionController extends Controller
{

    /**
     * Lists all Comision entities.
     *
     * @Route("/", name="admin_comision")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Comision')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Comision entity.
     *
     * @Route("/", name="admin_comision_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Comision:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Comision();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_comision_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Comision entity.
     *
     * @param Comision $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comision $entity)
    {
        $form = $this->createForm(new ComisionType(), $entity, array(
            'action' => $this->generateUrl('admin_comision_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Comision entity.
     *
     * @Route("/new", name="admin_comision_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Comision();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Comision entity.
     *
     * @Route("/{id}", name="admin_comision_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Comision')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comision entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Comision entity.
     *
     * @Route("/{id}/edit", name="admin_comision_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Comision')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comision entity.');
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
    * Creates a form to edit a Comision entity.
    *
    * @param Comision $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Comision $entity)
    {
        $form = $this->createForm(new ComisionType(), $entity, array(
            'action' => $this->generateUrl('admin_comision_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Comision entity.
     *
     * @Route("/{id}", name="admin_comision_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Comision:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Comision')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comision entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_comision_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Comision entity.
     *
     * @Route("/{id}", name="admin_comision_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Comision')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comision entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_comision'));
    }

    /**
     * Creates a form to delete a Comision entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_comision_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
