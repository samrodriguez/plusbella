<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Tratamiento;
use DGPlusbelleBundle\Form\TratamientoType;

/**
 * Tratamiento controller.
 *
 * @Route("/admin/tratamiento")
 */
class TratamientoController extends Controller
{

    /**
     * Lists all Tratamiento entities.
     *
     * @Route("/", name="admin_tratamiento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entity = new Tratamiento();
        $form = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Tratamiento')->findAll();

        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Tratamiento entity.
     *
     * @Route("/", name="admin_tratamiento_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Tratamiento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Tratamiento();
        $entity->setEstado(true);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_tratamiento', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Tratamiento entity.
     *
     * @param Tratamiento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tratamiento $entity)
    {
        $form = $this->createForm(new TratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_tratamiento_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Tratamiento entity.
     *
     * @Route("/new", name="admin_tratamiento_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tratamiento();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Tratamiento entity.
     *
     * @Route("/{id}", name="admin_tratamiento_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Tratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tratamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Tratamiento entity.
     *
     * @Route("/{id}/edit", name="admin_tratamiento_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Tratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tratamiento entity.');
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
    * Creates a form to edit a Tratamiento entity.
    *
    * @param Tratamiento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tratamiento $entity)
    {
        $form = $this->createForm(new TratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_tratamiento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tratamiento entity.
     *
     * @Route("/{id}", name="admin_tratamiento_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Tratamiento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Tratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tratamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_tratamiento_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Tratamiento entity.
     *
     * @Route("/{id}", name="admin_tratamiento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Tratamiento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tratamiento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_tratamiento'));
    }

    /**
     * Creates a form to delete a Tratamiento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tratamiento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
