<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Bitacora;
use DGPlusbelleBundle\Form\BitacoraType;

/**
 * Bitacora controller.
 *
 * @Route("/admin/bitacora")
 */
class BitacoraController extends Controller
{

    /**
     * Lists all Bitacora entities.
     *
     * @Route("/", name="admin_bitacora")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario= $this->get('security.token_storage')->getToken()->getUser();
        $entities = array(); 
        $userId = $usuario->getId();
        
        //if ($userId != 1) {
            $dql = "SELECT bi FROM DGPlusbelleBundle:Bitacora bi "
                    . "JOIN bi.usuario us "
                    . "JOIN us.persona per "
                    . "WHERE us.id = :id "
                    . "ORDER BY bi.fechaAccion DESC ";
            $entities = $em->createQuery($dql)
                       ->setParameter('id', $userId)
                       ->getResult();
        //} else {
            $entities = $em->getRepository('DGPlusbelleBundle:Bitacora')->findBy(array(), array('fechaAccion'=>'DESC'));
        //}
        
        return array(
            'usuario' => $usuario,
            'entities' => $entities,
        );
    } 
    
    /**
     * Creates a new Bitacora entity.
     *
     * @Route("/", name="admin_bitacora_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Bitacora:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Bitacora();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_bitacora_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Bitacora entity.
     *
     * @param Bitacora $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Bitacora $entity)
    {
        $form = $this->createForm(new BitacoraType(), $entity, array(
            'action' => $this->generateUrl('admin_bitacora_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Bitacora entity.
     *
     * @Route("/new", name="admin_bitacora_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Bitacora();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Bitacora entity.
     *
     * @Route("/{id}", name="admin_bitacora_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Bitacora')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bitacora entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Bitacora entity.
     *
     * @Route("/{id}/edit", name="admin_bitacora_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Bitacora')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bitacora entity.');
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
    * Creates a form to edit a Bitacora entity.
    *
    * @param Bitacora $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Bitacora $entity)
    {
        $form = $this->createForm(new BitacoraType(), $entity, array(
            'action' => $this->generateUrl('admin_bitacora_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Bitacora entity.
     *
     * @Route("/{id}", name="admin_bitacora_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Bitacora:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Bitacora')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bitacora entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_bitacora_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Bitacora entity.
     *
     * @Route("/{id}", name="admin_bitacora_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Bitacora')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Bitacora entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_bitacora'));
    }

    /**
     * Creates a form to delete a Bitacora entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_bitacora_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    
    
    
    /**
     * Creates a new Bitacora entity.
     *
     * @Route("/{mensaje}", name="admin_bitacora_guardar")
     * @Method("POST")
     * @Template()
     */
    public function guardarAction(Request $request,$mensaje)
    {
        $entity = new Bitacora();
        
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_bitacora_show', array('id' => $entity->getId())));
        
        
    }
}
