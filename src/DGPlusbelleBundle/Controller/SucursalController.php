<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Sucursal;
use DGPlusbelleBundle\Form\SucursalType;

/**
 * Sucursal controller.
 *
 * @Route("/admin/sucursal")
 */
class SucursalController extends Controller
{

    /**
     * Lists all Sucursal entities.
     *
     * @Route("/", name="admin_sucursal")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entity = new Sucursal();
        $form   = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Sucursal')->findAll();

        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Sucursal entity.
     *
     * @Route("/", name="admin_sucursal_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Sucursal:index.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Sucursal();
        $entity->setEstado(true);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_sucursal', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Sucursal entity.
     *
     * @param Sucursal $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Sucursal $entity)
    {
        $form = $this->createForm(new SucursalType(), $entity, array(
            'action' => $this->generateUrl('admin_sucursal_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Sucursal entity.
     *
     * @Route("/new", name="admin_sucursal_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Sucursal();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Sucursal entity.
     *
     * @Route("/{id}", name="admin_sucursal_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sucursal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Sucursal entity.
     *
     * @Route("/{id}/edit", name="admin_sucursal_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //echo "Es una petición AJAX";
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sucursal entity.');
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
    * Creates a form to edit a Sucursal entity.
    *
    * @param Sucursal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Sucursal $entity)
    {
        $form = $this->createForm(new SucursalType(), $entity, array(
            'action' => $this->generateUrl('admin_sucursal_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }
    /**
     * Edits an existing Sucursal entity.
     *
     * @Route("/{id}", name="admin_sucursal_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Sucursal:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sucursal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_sucursal', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Sucursal entity.
     *
     * @Route("/{id}", name="admin_sucursal_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        //$form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($id);
        $entity->setEstado(0);
        
        $em->persist($entity);
        $em->flush();
        
        $exito['regs']=0;
        
        return new Response(json_encode($exito));
        
       /* if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Sucursal entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_sucursal'));  */
    }

    /**
     * Creates a form to delete a Sucursal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_sucursal_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Deletes a Sucursal entity.
     *
     * @Route("/desactivar_sucursal/{id}", name="admin_sucursal_desactivar", options={"expose"=true})
     * @Method("GET")
     */
    public function desactivarAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        //$form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($id);
        $entity->setEstado(0);
        
        $em->persist($entity);
        $em->flush();
        
        $exito['regs']=0;
        
        return new Response(json_encode($exito));
        
    }
}
