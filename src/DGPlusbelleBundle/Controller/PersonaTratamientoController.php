<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\PersonaTratamiento;
use DGPlusbelleBundle\Form\PersonaTratamientoType;
use DGPlusbelleBundle\Entity\SeguimientoTratamiento;

/**
 * PersonaTratamiento controller.
 *
 * @Route("/admin/personatratamiento")
 */
class PersonaTratamientoController extends Controller
{

    /**
     * Lists all PersonaTratamiento entities.
     *
     * @Route("/", name="admin_personatratamiento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entity = new PersonaTratamiento();
        $form = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->findAll();

        return array(
            'entities' => $entities,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new PersonaTratamiento entity.
     *
     * @Route("/", name="admin_personatratamiento_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:PersonaTratamiento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new PersonaTratamiento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $entity->setFechaRegistro(new \DateTime('now'));
        $entity->setFechaVenta(new \DateTime('now'));
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $seguimiento = new SeguimientoTratamiento;
            $seguimiento->setPersonaTratamiento($entity);
            $seguimiento->setNumSesion(0);
            $em->persist($seguimiento);
            $em->flush();
            
            return $this->redirect($this->generateUrl('admin_personatratamiento', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a PersonaTratamiento entity.
     *
     * @param PersonaTratamiento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PersonaTratamiento $entity)
    {
        $form = $this->createForm(new PersonaTratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_personatratamiento_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new PersonaTratamiento entity.
     *
     * @Route("/new", name="admin_personatratamiento_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new PersonaTratamiento();
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $id= $request->get('id');
        $id = substr($id, 1);
        
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        //Seteo del paciente en la entidad
        
        $persona=$paciente->getPersona();
        //var_dump($persona);
        $entity->setPaciente($persona);
        //var_dump($entity);
        
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a PersonaTratamiento entity.
     *
     * @Route("/{id}", name="admin_personatratamiento_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonaTratamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing PersonaTratamiento entity.
     *
     * @Route("/{id}/edit", name="admin_personatratamiento_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonaTratamiento entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
                        'id'=>"P".$entity->getPaciente()->getPaciente()[0]->getId(),
        );
    }

    /**
    * Creates a form to edit a PersonaTratamiento entity.
    *
    * @param PersonaTratamiento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PersonaTratamiento $entity)
    {
        $form = $this->createForm(new PersonaTratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_personatratamiento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }
    /**
     * Edits an existing PersonaTratamiento entity.
     *
     * @Route("/{id}", name="admin_personatratamiento_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:PersonaTratamiento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonaTratamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_personatratamiento_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a PersonaTratamiento entity.
     *
     * @Route("/{id}", name="admin_personatratamiento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PersonaTratamiento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_personatratamiento'));
    }

    /**
     * Creates a form to delete a PersonaTratamiento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_personatratamiento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    
    /**
    * Deletes a Cuenta entity.
    *
    * @Route("/costo_tratamiento/{id}", name="admin_costo_tratamiento", options={"expose"=true})
    * @Method("GET")
    */
   public function costotratamAction(Request $request, $id)
   {

       $em = $this->getDoctrine()->getManager();
       $entity = $em->getRepository('DGPlusbelleBundle:Tratamiento')->find($id);

       $exito=array();

       if($entity!=null){
          
           $exito['regs']=$entity->getCosto();
       }
       else{
           $exito['regs']=-1;
       }

       return new Response(json_encode($exito));
       
   }  
    
}
