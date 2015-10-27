<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Empleado;
use DGPlusbelleBundle\Entity\Persona;
use DGPlusbelleBundle\Form\EmpleadoType;
use DGPlusbelleBundle\Form\PersonaType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
/**
 * Empleado controller.
 *
 * @Route("/admin/empleado")
 */
class EmpleadoController extends Controller
{

    /**
     * Lists all Empleado entities.
     *
     * @Route("/", name="admin_empleado")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getManager();
        
        $sql = "select * "
                . "from empleado ";
        
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('descripcion','descripcion');
        $rsm->addScalarResult('codigo','codigo');
        $rsm->addScalarResult('fechacreacion','fechacreacion');
        $rsm->addScalarResult('fechafinalizacion','fechafinalizacion');
        $rsm->addScalarResult('observacion','observacion');
        
        $entities = $em->getRepository('DGPlusbelleBundle:Empleado')->findAll();
        $empleados = $em->createNativeQuery($sql, $rsm)
                      ->setParameter(1,1)
                    //->setParameter(1, $usuario->getIdEmpleado()->getId())
                  //
                    ->getResult();
        

        return array(
            'entities' => $entities,
            'empleados' => $empleados,
        );
    }
    /**
     * Creates a new Empleado entity.
     *
     * @Route("/", name="admin_empleado_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Empleado:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Empleado();
        //$entity->setEstado(true);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
       /* $parameters = $request->request->all();
        foreach($parameters as $p){
            $primerNombre = $p['primerNombre'];
            $segundoNombre = $p['segundoNombre'];
            $primerApellido = $p['primerApellido'];
            $segundoApellido = $p['segundoApellido'];
            $apellidoCasada = $p['apellidoCasada'];
            $direccion = $p['direccion'];
            $telefono = $p['telefono'];
            $email = $p['email'];
        }*/
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_empleado_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Empleado entity.
     *
     * @param Empleado $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Empleado $entity)
    {
      /* $persona = new Persona();
        $formPersona = new PersonaType();*/
        $form = $this->createForm(new EmpleadoType(), $entity, array(
            'action' => $this->generateUrl('admin_empleado_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-primary')
             ));

        return $form;
    }

    /**
     * Displays a form to create a new Empleado entity.
     *
     * @Route("/new", name="admin_empleado_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Empleado();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Empleado entity.
     *
     * @Route("/{id}", name="admin_empleado_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Empleado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Empleado entity.
     *
     * @Route("/{id}/edit", name="admin_empleado_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Empleado entity.');
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
    * Creates a form to edit a Empleado entity.
    *
    * @param Empleado $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Empleado $entity)
    {
        $form = $this->createForm(new EmpleadoType(), $entity, array(
            'action' => $this->generateUrl('admin_empleado_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Empleado entity.
     *
     * @Route("/{id}", name="admin_empleado_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Empleado:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Empleado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_empleado_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Empleado entity.
     *
     * @Route("/{id}", name="admin_empleado_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Empleado entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_empleado'));
    }

    /**
     * Creates a form to delete a Empleado entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_empleado_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
