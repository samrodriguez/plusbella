<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Paquete;
//use DGPlusbelleBundle\Entity\PaqueteTratamiento;
use DGPlusbelleBundle\Form\PaqueteType;
//use DGPlusbelleBundle\Form\PaqueteTratamientoType;

/**
 * Paquete controller.
 *
 * @Route("/admin/paquete")
 */
class PaqueteController extends Controller
{

    /**
     * Lists all Paquete entities.
     *
     * @Route("/", name="admin_paquete")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entity = new Paquete();
        $form = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Paquete')->findAll();

        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Paquete entity.
     *
     * @Route("/", name="admin_paquete_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Paquete:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Paquete();
        $entity->setEstado(true);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se registro un nuevo paquete",$usuario->getId());
            
            return $this->redirect($this->generateUrl('admin_paquete', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Paquete entity.
     *
     * @param Paquete $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Paquete $entity)
    {
        $form = $this->createForm(new PaqueteType(), $entity, array(
            'action' => $this->generateUrl('admin_paquete_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Paquete entity.
     *
     * @Route("/new", name="admin_paquete_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Paquete();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Paquete entity.
     *
     * @Route("/{id}", name="admin_paquete_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Paquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paquete entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Paquete entity.
     *
     * @Route("/{id}/edit", name="admin_paquete_edit", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Paquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paquete entity.');
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
    * Creates a form to edit a Paquete entity.
    *
    * @param Paquete $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Paquete $entity)
    {
        $form = $this->createForm(new PaqueteType(), $entity, array(
            'action' => $this->generateUrl('admin_paquete_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }
    /**
     * Edits an existing Paquete entity.
     *
     * @Route("/{id}", name="admin_paquete_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Paquete:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Paquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paquete entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se actualizo informacion de un paquete",$usuario->getId());
            
            return $this->redirect($this->generateUrl('admin_paquete'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Paquete entity.
     *
     * @Route("/{id}", name="admin_paquete_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Paquete')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Paquete entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_paquete'));
    }

    /**
     * Creates a form to delete a Paquete entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_paquete_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
        
    /**
     * Deletes a Paquete entity.
     *
     * @Route("/desactivar_paquete/{id}", name="admin_paquete_desactivar", options={"expose"=true})
     * @Method("GET")
     */
    public function desactivarAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        //$form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Paquete')->find($id);
        
         if($entity->getEstado()==0){
            $entity->setEstado(1);
            $exito['regs']=1;//registro activado
        }
        else{
            $entity->setEstado(0);
            $exito['regs']=0;//registro desactivado
        }
        
        $em->persist($entity);
        $em->flush();
        
        return new Response(json_encode($exito));
        
    }
    
    
    /**
     * Detalle paquete.
     *
     * @Route("/detalle_paquete/{id}", name="admin_paquete_detalle", options={"expose"=true})
     * @Method("GET")
     */
    public function detallepaqueteAction(Request $request, $id)
    {
        
    }
    
    
    
    
}
