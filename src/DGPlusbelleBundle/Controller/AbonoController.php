<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Abono;
use DGPlusbelleBundle\Form\AbonoType;

/**
 * Abono controller.
 *
 * @Route("/admin/abono")
 */
class AbonoController extends Controller
{

    /**
     * Lists all Abono entities.
     *
     * @Route("/", name="admin_abono")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Abono')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Abono entity.
     *
     * @Route("/", name="admin_abono_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Abono:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Abono();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $entity->setFechaAbono(new \DateTime('now'));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_abono_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Abono entity.
     *
     * @param Abono $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Abono $entity)
    {
        $form = $this->createForm(new AbonoType(), $entity, array(
            'action' => $this->generateUrl('admin_abono_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Abono entity.
     *
     * @Route("/new", name="admin_abono_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Abono();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Abono entity.
     *
     * @Route("/{id}", name="admin_abono_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Abono')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Abono entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Abono entity.
     *
     * @Route("/{id}/edit", name="admin_abono_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Abono')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Abono entity.');
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
    * Creates a form to edit a Abono entity.
    *
    * @param Abono $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Abono $entity)
    {
        $form = $this->createForm(new AbonoType(), $entity, array(
            'action' => $this->generateUrl('admin_abono_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Abono entity.
     *
     * @Route("/{id}", name="admin_abono_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Abono:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Abono')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Abono entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_abono_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Abono entity.
     *
     * @Route("/{id}", name="admin_abono_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Abono')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Abono entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_abono'));
    }

    /**
     * Creates a form to delete a Abono entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_abono_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
