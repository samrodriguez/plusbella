<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\CierreAdministrativo;
use DGPlusbelleBundle\Form\CierreAdministrativoType;

/**
 * CierreAdministrativo controller.
 *
 * @Route("/admin/cierreadministrativo")
 */
class CierreAdministrativoController extends Controller
{

    /**
     * Lists all entities.
     *
     * @Route("/", name="admin_cierreadministrativo")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(){
        $entity = new CierreAdministrativo();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createCreateForm($entity);
        $entities = $em->getRepository('DGPlusbelleBundle:CierreAdministrativo')->findAll();

        return array(
            'entities' => $entities,
            'form'      => $form->createView(),
        );
    }
    
    /**
     * Creates a new CierreAdministrativo entity.
     *
     * @Route("/", name="admin_cierreadministrativo_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:CierreAdministrativo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CierreAdministrativo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

//            return $this->redirect($this->generateUrl('admin_cierreadministrativo_show', array('id' => $entity->getId())));
            return $this->redirect($this->generateUrl('admin_cierreadministrativo'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a CierreAdministrativo entity.
     *
     * @param CierreAdministrativo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CierreAdministrativo $entity)
    {
        $form = $this->createForm(new CierreAdministrativoType(), $entity, array(
            'action' => $this->generateUrl('admin_cierreadministrativo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar','attr'=>array('class'=> 'btn btn-primary btn-sm btn')));

        return $form;
    }

    /**
     * Displays a form to create a new CierreAdministrativo entity.
     *
     * @Route("/new", name="admin_cierreadministrativo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CierreAdministrativo();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a CierreAdministrativo entity.
     *
     * @Route("/{id}", name="admin_cierreadministrativo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:CierreAdministrativo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CierreAdministrativo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CierreAdministrativo entity.
     *
     * @Route("/{id}/edit", name="admin_cierreadministrativo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:CierreAdministrativo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CierreAdministrativo entity.');
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
    * Creates a form to edit a CierreAdministrativo entity.
    *
    * @param CierreAdministrativo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CierreAdministrativo $entity)
    {
        $form = $this->createForm(new CierreAdministrativoType(), $entity, array(
            'action' => $this->generateUrl('admin_cierreadministrativo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Editar','attr'=>array('class'=> 'btn btn-primary btn-sm btn')));

        return $form;
    }
    /**
     * Edits an existing CierreAdministrativo entity.
     *
     * @Route("/{id}", name="admin_cierreadministrativo_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:CierreAdministrativo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:CierreAdministrativo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CierreAdministrativo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //return $this->redirect($this->generateUrl('admin_cierreadministrativo_edit', array('id' => $id)));
            return $this->redirect($this->generateUrl('admin_cierreadministrativo'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CierreAdministrativo entity.
     *
     * @Route("/{id}", name="admin_cierreadministrativo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:CierreAdministrativo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CierreAdministrativo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_cierreadministrativo'));
    }

    /**
     * Creates a form to delete a CierreAdministrativo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_cierreadministrativo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    
    
    
    
    /**
     * @Route("/borrar/cierre/administrativo/get/", name="borrar_cierre", options={"expose"=true})
     * @Method("POST")
     */
    public function borrarCierreAction(Request $request) {
        
        $idCierre = $request->get('id');
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $cierre = $em->getRepository("DGPlusbelleBundle:CierreAdministrativo")->find($idCierre);
        //var_dump($cierre);
        if(count($cierre)!=0){
            $em->remove($cierre);
            $em->flush();
            return new Response(json_encode(0));
        }
        else{
            return new Response(json_encode(1));
        }
        
        
        
    }
}
