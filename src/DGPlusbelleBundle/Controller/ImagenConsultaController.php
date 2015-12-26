<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\ImagenConsulta;
use DGPlusbelleBundle\Form\ImagenConsultaType;

/**
 * ImagenConsulta controller.
 *
 * @Route("/admin/imagenconsulta")
 */
class ImagenConsultaController extends Controller
{

    /**
     * Lists all ImagenConsulta entities.
     *
     * @Route("/", name="admin_imagenconsulta")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:ImagenConsulta')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ImagenConsulta entity.
     *
     * @Route("/", name="admin_imagenconsulta_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:ImagenConsulta:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ImagenConsulta();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_imagenconsulta_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ImagenConsulta entity.
     *
     * @param ImagenConsulta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ImagenConsulta $entity)
    {
        $form = $this->createForm(new ImagenConsultaType(), $entity, array(
            'action' => $this->generateUrl('admin_imagenconsulta_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ImagenConsulta entity.
     *
     * @Route("/new", name="admin_imagenconsulta_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ImagenConsulta();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ImagenConsulta entity.
     *
     * @Route("/{id}", name="admin_imagenconsulta_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ImagenConsulta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ImagenConsulta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ImagenConsulta entity.
     *
     * @Route("/{id}/edit", name="admin_imagenconsulta_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ImagenConsulta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ImagenConsulta entity.');
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
    * Creates a form to edit a ImagenConsulta entity.
    *
    * @param ImagenConsulta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ImagenConsulta $entity)
    {
        $form = $this->createForm(new ImagenConsultaType(), $entity, array(
            'action' => $this->generateUrl('admin_imagenconsulta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ImagenConsulta entity.
     *
     * @Route("/{id}", name="admin_imagenconsulta_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:ImagenConsulta:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ImagenConsulta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ImagenConsulta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_imagenconsulta_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ImagenConsulta entity.
     *
     * @Route("/{id}", name="admin_imagenconsulta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:ImagenConsulta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ImagenConsulta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_imagenconsulta'));
    }

    /**
     * Creates a form to delete a ImagenConsulta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_imagenconsulta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    
    
    
    
    
}
