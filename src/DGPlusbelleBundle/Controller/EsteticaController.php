<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Estetica;
use DGPlusbelleBundle\Form\EsteticaType;

/**
 * Estetica controller.
 *
 * @Route("/admin/estetica")
 */
class EsteticaController extends Controller
{

    /**
     * Lists all Estetica entities.
     *
     * @Route("/", name="admin_estetica")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Estetica')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Estetica entity.
     *
     * @Route("/", name="admin_estetica_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Estetica:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Estetica();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_estetica_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Estetica entity.
     *
     * @param Estetica $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Estetica $entity)
    {
        $form = $this->createForm(new EsteticaType(), $entity, array(
            'action' => $this->generateUrl('admin_estetica_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Estetica entity.
     *
     * @Route("/new", name="admin_estetica_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Estetica();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Estetica entity.
     *
     * @Route("/{id}", name="admin_estetica_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Estetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estetica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Estetica entity.
     *
     * @Route("/{id}/edit", name="admin_estetica_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Estetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estetica entity.');
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
    * Creates a form to edit a Estetica entity.
    *
    * @param Estetica $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Estetica $entity)
    {
        $form = $this->createForm(new EsteticaType(), $entity, array(
            'action' => $this->generateUrl('admin_estetica_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Estetica entity.
     *
     * @Route("/{id}", name="admin_estetica_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Estetica:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Estetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estetica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_estetica_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Estetica entity.
     *
     * @Route("/{id}", name="admin_estetica_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Estetica')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Estetica entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_estetica'));
    }

    /**
     * Creates a form to delete a Estetica entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_estetica_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
