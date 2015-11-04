<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Consulta;
use DGPlusbelleBundle\Entity\Expediente;
use DGPlusbelleBundle\Entity\HistorialClinico;
use DGPlusbelleBundle\Form\ConsultaType;

/**
 * Consulta controller.
 *
 * @Route("/admin/consulta")
 */
class ConsultaController extends Controller
{

    /**
     * Lists all Consulta de emergencia entities.
     *
     * @Route("/", name="admin_consulta")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Consulta();
        $form   = $this->createCreateForm($entity);
        $entities = $em->getRepository('DGPlusbelleBundle:Consulta')->findAll();

        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
            'tipo'  => 1,
        );
    }
    
    /**
     * Lists all Consulta diaria entities.
     *
     * @Route("/diaria", name="admin_consulta_diaria")
     * @Method("GET")
     * @Template("DGPlusbelleBundle:Consulta:index.html.twig")
     */
    public function indexDiariaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Consulta();
        $form   = $this->createCreateForm($entity);
        //$entities = $em->getRepository('DGPlusbelleBundle:Consulta')->findAll();
        $dql = "SELECT c FROM DGPlusbelleBundle:Consulta c WHERE c.tipoConsulta= :tipo";
        $entities = $em->createQuery($dql)
                       ->setParameter('tipo',1)
                       ->getResult();
               //var_dump($entities);
        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
            'tipo'  => 2,
        );
    }
    
    /**
     * Creates a new Consulta entity.
     *
     * @Route("/", name="admin_consulta_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Consulta:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Consulta();
        $em = $this->getDoctrine()->getManager();
        //Obtiene el usuario
        $user = $this->get('security.token_storage')->getToken()->getUser();
        //Entidades para insertar en el proceso de la consulta de emergencia
        $historial = new HistorialClinico();
        $expediente = new Expediente();
        //Seteo de valores
        $expediente->setFechaCreacion(new \DateTime('now'));
        $expediente->setHoraCreacion(new \DateTime('now'));
        $expediente->setEstado(true);
        //$historial->setConsulta($entity);
        
        //Tipo de consulta actica, emergencia
        $dql = "SELECT tc FROM DGPlusbelleBundle:TipoConsulta tc WHERE tc.estado = :estado AND tc.id=:id";
        $tipoConsulta= $em->createQuery($dql)
                       ->setParameters(array('estado'=>1,'id'=>1))
                       ->getResult();
               //var_dump($tipoConsulta[0]);
               //die();
        $tipoConsulta = $tipoConsulta[0];
        //var_dump($tipoConsulta);
               //die();
        $entity->setTipoConsulta($tipoConsulta);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $paciente = $entity->getPaciente();
            $paciente->setEstado(true);
            
            $apellido = $paciente->getPersona()->getPrimerApellido();
            $nombre= $paciente->getPersona()->getPrimerNombre();
            
            //Generacion del numero de expediente
            $numeroExp = $nombre[0].$apellido[0].date("Y");
            
            $dql = "SELECT COUNT(exp)+1 FROM DGPlusbelleBundle:Expediente exp WHERE exp.numero LIKE :numero";
            
            $num = $em->createQuery($dql)
                       ->setParameter('numero','%'.$numeroExp.'%')
                       ->getResult();
            //var_dump($user);
            $numString = $num[0]["1"];
            //var_dump($numString);
            
            switch($numString){
                case 1:
                        $numeroExp .= "00".$numString;
                    break;
                case 2:
                        $numeroExp .= "0".$numString;
                    break;
                case 3:
                        $numeroExp .= $numString;
                    break;
            }
            
            $expediente->setNumero($numeroExp);
            $expediente->setPaciente($paciente);
            $expediente->setUsuario($user);
            
            //$historial->setConsulta($consulta);
            //$historial->setExpediente($expediente);
            $em->persist($expediente);
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_consulta' ));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Consulta entity.
     *
     * @param Consulta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Consulta $entity)
    {
        $form = $this->createForm(new ConsultaType(), $entity, array(
            'action' => $this->generateUrl('admin_consulta_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Consulta entity.
     *
     * @Route("/new", name="admin_consulta_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Consulta();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Consulta entity.
     *
     * @Route("/{id}", name="admin_consulta_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Consulta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Consulta entity.
     *
     * @Route("/{id}/edit", name="admin_consulta_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Consulta entity.');
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
    * Creates a form to edit a Consulta entity.
    *
    * @param Consulta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Consulta $entity)
    {
        $form = $this->createForm(new ConsultaType(), $entity, array(
            'action' => $this->generateUrl('admin_consulta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }
    /**
     * Edits an existing Consulta entity.
     *
     * @Route("/{id}", name="admin_consulta_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Consulta:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Consulta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_consulta', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Consulta entity.
     *
     * @Route("/{id}", name="admin_consulta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Consulta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_consulta'));
    }

    /**
     * Creates a form to delete a Consulta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_consulta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
