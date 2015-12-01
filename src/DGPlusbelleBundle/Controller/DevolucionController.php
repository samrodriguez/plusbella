<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Devolucion;
use DGPlusbelleBundle\Form\DevolucionType;

/**
 * Devolucion controller.
 *
 * @Route("/admin/devolucion")
 */
class DevolucionController extends Controller
{

    /**
     * Lists all Devolucion entities.
     *
     * @Route("/", name="admin_devolucion")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entity = new Devolucion();
        $form = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Devolucion')->findAll();

        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Devolucion entity.
     *
     * @Route("/", name="admin_devolucion_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Devolucion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Devolucion();
        $entity->setfechaDevolucion(new \DateTime('now'));
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_devolucion', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Devolucion entity.
     *
     * @param Devolucion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Devolucion $entity)
    {
        $form = $this->createForm(new DevolucionType(), $entity, array(
            'action' => $this->generateUrl('admin_devolucion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Devolucion entity.
     *
     * @Route("/new", name="admin_devolucion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Devolucion();
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del paciente
        $request = $this->getRequest();
        $id= $request->get('id');
        $id = substr($id, 1);
        //Busqueda del paciente
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        
        //$persona=$paciente->getPersona();
        ///var_dump($persona);
        $entity->setPaciente($paciente);
        
        
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Devolucion entity.
     *
     * @Route("/{id}", name="admin_devolucion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Devolucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Devolucion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Devolucion entity.
     *
     * @Route("/{id}/edit", name="admin_devolucion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Devolucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Devolucion entity.');
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
    * Creates a form to edit a Devolucion entity.
    *
    * @param Devolucion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Devolucion $entity)
    {
        $form = $this->createForm(new DevolucionType(), $entity, array(
            'action' => $this->generateUrl('admin_devolucion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit','submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }
    /**
     * Edits an existing Devolucion entity.
     *
     * @Route("/{id}", name="admin_devolucion_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Devolucion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Devolucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Devolucion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_devolucion'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Devolucion entity.
     *
     * @Route("/{id}", name="admin_devolucion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Devolucion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Devolucion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_devolucion'));
    }

    /**
     * Creates a form to delete a Devolucion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_devolucion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
