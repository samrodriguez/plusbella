<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\SeguimientoPaquete;
use DGPlusbelleBundle\Entity\VentaPaquete;
use DGPlusbelleBundle\Entity\Paciente;
use DGPlusbelleBundle\Form\VentaPaqueteType;

/**
 * VentaPaquete controller.
 *
 * @Route("/admin/ventapaquete")
 */
class VentaPaqueteController extends Controller
{

    /**
     * Lists all VentaPaquete entities.
     *
     * @Route("/", name="admin_ventapaquete")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new VentaPaquete();
        $form   = $this->createCreateForm($entity);
        $entities = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->findAll();

        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new VentaPaquete entity.
     *
     * @Route("/", name="admin_ventapaquete_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:VentaPaquete:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new VentaPaquete();
       
        //$entity->getFechaVenta();
        $em = $this->getDoctrine()->getManager();//
        
        //Obtener el usuario segun el id
        //$usuario = $em->getRepository('DGPlusbelleBundle:Usuario')->findBy(array("id"=>1));
        $usuario= $this->get('security.token_storage')->getToken()->getUser();

        $dql = "SELECT suc.id
            FROM DGPlusbelleBundle:Usuario u
                    JOIN u.persona per
                    JOIN per.empleado emp
                    JOIN emp.sucursal suc
            WHERE u.id=:id";
        
        $entities = $em->createQuery($dql)
                       ->setParameter('id',1)
                       ->getResult();


        $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array("id"=>$entities[0]["id"]));
        $entity->setSucursal($sucursal[0]);
        $entity->setUsuario($usuario);
        //var_dump($usuario);

        //die();

        $entity->setFechaRegistro(new \DateTime('now'));
        $entity->setFechaVenta(new \DateTime('now'));
        $entity->setEstado(1);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $paqueteTratamiento = $em->getRepository('DGPlusbelleBundle:PaqueteTratamiento')->findBy(array('paquete' => $entity->getPaquete()->getId()));
            var_dump($paqueteTratamiento);
            die();
            foreach($paqueteTratamiento as $pt){
                $seguimiento = new SeguimientoPaquete;
                $seguimiento->setVentaPaquete($entity);
                $seguimiento->setNumSesion(0);
                $seguimiento->setTratamiento($pt->getTratamiento());
                $em->persist($seguimiento);
                $em->flush();
            }
                
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se registro la venta de un paquete",$usuario->getId());
            
            return $this->redirect($this->generateUrl('admin_ventapaquete'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a VentaPaquete entity.
     *
     * @param VentaPaquete $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(VentaPaquete $entity)
    {
        $form = $this->createForm(new VentaPaqueteType(), $entity, array(
            'action' => $this->generateUrl('admin_ventapaquete_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new VentaPaquete entity.
     *
     * @Route("/new", name="admin_ventapaquete_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new VentaPaquete();
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del paciente
        $request = $this->getRequest();
        $id= $request->get('id');
        $id = substr($id, 1);
        //Busqueda del paciente
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        //Seteo del paciente en la entidad
        $persona=$paciente->getPersona();
        $entity->setPaciente($persona);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a VentaPaquete entity.
     *
     * @Route("/{id}", name="admin_ventapaquete_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VentaPaquete entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing VentaPaquete entity.
     *
     * @Route("/{id}/edit", name="admin_ventapaquete_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VentaPaquete entity.');
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
    * Creates a form to edit a VentaPaquete entity.
    *
    * @param VentaPaquete $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VentaPaquete $entity)
    {
        $form = $this->createForm(new VentaPaqueteType(), $entity, array(
            'action' => $this->generateUrl('admin_ventapaquete_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-primary btn-sm')));

        return $form;
    }
    /**
     * Edits an existing VentaPaquete entity.
     *
     * @Route("/{id}", name="admin_ventapaquete_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:VentaPaquete:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);


        //Obtener el usuario segun el id
        $usuario = $em->getRepository('DGPlusbelleBundle:Usuario')->findBy(array("id"=>1));

        $dql = "SELECT suc.id
            FROM DGPlusbelleBundle:Usuario u
                    JOIN u.persona per
                    JOIN per.empleado emp
                    JOIN emp.sucursal suc
            WHERE u.id=:id";
        
        $entities = $em->createQuery($dql)
                       ->setParameter('id',1)
                       ->getResult();


        $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array("id"=>$entities[0]["id"]));
        $entity->setSucursal($sucursal[0]);
        $entity->setUsuario($usuario[0]);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VentaPaquete entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ventapaquete'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a VentaPaquete entity.
     *
     * @Route("/{id}", name="admin_ventapaquete_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find VentaPaquete entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ventapaquete'));
    }







    /**
     * Creates a form to delete a VentaPaquete entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ventapaquete_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }




    /**
     * Lists all VentaPaquete entities.
     *
     * @Route("/ventapaquete/pacientes", name="admin_ventapaquete_pacientes")
     * @Method("GET")
     * @Template()
     */
    public function ventapaqueteAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Paciente')->findAll();

        return array(
            'pacientes' => $entities,
        );


    }
    
    
    



}
