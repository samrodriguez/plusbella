<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Paciente;
use DGPlusbelleBundle\Entity\Expediente;
use DGPlusbelleBundle\Form\PacienteType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Paciente controller.
 *
 * @Route("/admin/paciente")
 */
class PacienteController extends Controller
{

    /**
     * Lists all Paciente entities.
     *
     * @Route("/", name="admin_paciente")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entity = new Paciente();
        $form = $this->createCreateForm($entity);
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getManager();
        
        $sql = "select per.nombres as pnombre, per.apellidos as papellido,  "
                . "per.direccion as direccion, per.telefono as tel, per.email as email, pac.id as idpac, pac.dui as dui, pac.estado_civil as ecivil, pac.sexo as sexo, pac.ocupacion as ocupacion, "
                . "pac.lugar_trabajo as lugarTrabajo, pac.fecha_nacimiento as fechaNacimiento, pac.referido_por as referidoPor "
                . "from paciente pac inner join persona per on pac.persona = per.id order by per.apellidos";
        
        $rsm->addScalarResult('idpac','idpac');
        $rsm->addScalarResult('pnombre','pnombre');
        //$rsm->addScalarResult('snombre','snombre');
        $rsm->addScalarResult('papellido','papellido');
        //$rsm->addScalarResult('sapellido','sapellido');
        //$rsm->addScalarResult('casada','casada');
        $rsm->addScalarResult('direccion','direccion');
        $rsm->addScalarResult('tel','tel');
        $rsm->addScalarResult('email','email');
        $rsm->addScalarResult('dui','dui');
        $rsm->addScalarResult('ecivil','ecivil');
        $rsm->addScalarResult('sexo','sexo');
        $rsm->addScalarResult('ocupacion','ocupacion');
        $rsm->addScalarResult('lugarTrabajo','lugarTrabajo');
        $rsm->addScalarResult('fechaNacimiento','fechaNacimiento');
        $rsm->addScalarResult('referidoPor','referidoPor');
        
        
        $pacientes = $em->createNativeQuery($sql, $rsm)
                    ->getResult();
        
        return array(
            //'entities' => $entities,
            'pacientes' => $pacientes,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Paciente entity.
     *
     * @Route("/", name="admin_paciente_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Paciente:new.html.twig")
     */
    public function createAction(Request $request)
    {
       // $persona = new Persona();
        
        $entity = new Paciente();
        $entity->setEstado(true);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        $nombres = $entity->getPersona()->getNombres();
        $apellidos = $entity->getPersona()->getApellidos();

        if ($form->isValid()) {
           //$entity->setEstado(TRUE);
            $em = $this->getDoctrine()->getManager();
            $entity->getPersona()->setNombres(ucfirst($nombres));
            $entity->getPersona()->setApellidos(ucfirst($apellidos));
            
            $em->persist($entity);
            $this->generarExpediente($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_paciente', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Paciente entity.
     *
     * @param Paciente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Paciente $entity)
    {
        $form = $this->createForm(new PacienteType(), $entity, array(
            'action' => $this->generateUrl('admin_paciente_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')
                                                 
         ));

        return $form;
    }

    /**
     * Displays a form to create a new Paciente entity.
     *
     * @Route("/new", name="admin_paciente_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Paciente();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Paciente entity.
     *
     * @Route("/{id}", name="admin_paciente_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paciente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Paciente entity.
     *
     * @Route("/{id}/edit", name="admin_paciente_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $id= substr($id, 1);
        $entity = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paciente entity.');
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
    * Creates a form to edit a Paciente entity.
    *
    * @param Paciente $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Paciente $entity)
    {
        $form = $this->createForm(new PacienteType(), $entity, array(
            'action' => $this->generateUrl('admin_paciente_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }
    /**
     * Edits an existing Paciente entity.
     *
     * @Route("/{id}", name="admin_paciente_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Paciente:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paciente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_paciente'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Paciente entity.
     *
     * @Route("/{id}", name="admin_paciente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Paciente entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_paciente'));
    }

    /**
     * Creates a form to delete a Paciente entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_paciente_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Metodo que sirve para generar el expediente del paciente
     *
     * @param \DGPlusbelleBundle\Entity\Paciente $paciente
     *
     */
    private function generarExpediente(Paciente $paciente)
    {
        $em = $this->getDoctrine()->getManager();
        
        $expediente = new Expediente();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        // Obtencion del apellidos  y nombres del paciente
        $apellido = $paciente->getPersona()->getApellidos();
        $nombre = $paciente->getPersona()->getNombres();

        //Generacion del numero de expediente
        $numeroExp = $nombre[0].$apellido[0].date("Y");

        $dql = "SELECT COUNT(exp)+1 FROM DGPlusbelleBundle:Expediente exp WHERE exp.numero LIKE :numero";

        $num = $em->createQuery($dql)
                   ->setParameter('numero','%'.$numeroExp.'%')
                   ->getResult();
        //var_dump($user);
        $numString = $num[0]["1"];
        //var_dump($numString);

        switch(strlen($numString)){
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
        
        //Seteo de valores del expediente
        $expediente->setFechaCreacion(new \DateTime('now'));
        $expediente->setHoraCreacion(new \DateTime('now'));
        $expediente->setEstado(true);
        $expediente->setNumero($numeroExp);
        $expediente->setPaciente($paciente);
        $expediente->setUsuario($user);

        $em->persist($expediente);
    }
}
