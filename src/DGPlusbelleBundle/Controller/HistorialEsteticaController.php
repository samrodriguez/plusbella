<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\HistorialEstetica;
use DGPlusbelleBundle\Form\HistorialEsteticaType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * HistorialEstetica controller.
 *
 * @Route("/admin/historial-estetica")
 */
class HistorialEsteticaController extends Controller
{

    /**
     * Lists all HistorialEstetica entities.
     *
     * @Route("/", name="admin_historial-estetica")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:HistorialEstetica')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Lists all Consultas de estetica paciente.
     *
     * @Route("/consulta-estetica", name="admin_consultas_paciente")
     * @Method("GET")
     */
    public function ConsultasPacienteAction()
    {
        return $this->render('DGPlusbelleBundle:HistorialEstetica:consulta_paciente.html.twig');     
    }
    
    /**
     * Creates a new HistorialEstetica entity.
     *
     * @Route("/", name="admin_historial-estetica_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:HistorialEstetica:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new HistorialEstetica();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_historial-estetica_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a HistorialEstetica entity.
     *
     * @param HistorialEstetica $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(HistorialEstetica $entity)
    {
        $form = $this->createForm(new HistorialEsteticaType(), $entity, array(
            'action' => $this->generateUrl('admin_historial-estetica_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new HistorialEstetica entity.
     *
     * @Route("/new", name="admin_historial-estetica_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new HistorialEstetica();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a HistorialEstetica entity.
     *
     * @Route("/{id}", name="admin_historial-estetica_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:HistorialEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HistorialEstetica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing HistorialEstetica entity.
     *
     * @Route("/{id}/edit", name="admin_historial-estetica_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:HistorialEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HistorialEstetica entity.');
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
    * Creates a form to edit a HistorialEstetica entity.
    *
    * @param HistorialEstetica $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(HistorialEstetica $entity)
    {
        $form = $this->createForm(new HistorialEsteticaType(), $entity, array(
            'action' => $this->generateUrl('admin_historial-estetica_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing HistorialEstetica entity.
     *
     * @Route("/{id}", name="admin_historial-estetica_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:HistorialEstetica:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:HistorialEstetica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HistorialEstetica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_historial-estetica_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a HistorialEstetica entity.
     *
     * @Route("/{id}", name="admin_historial-estetica_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:HistorialEstetica')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find HistorialEstetica entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_historial-estetica'));
    }

    /**
     * Creates a form to delete a HistorialEstetica entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_historial-estetica_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/consulta-paciente/data", name="admin_consultas_paciente_data")
     */
    public function dataConsultaPacienteAction(Request $request)
    {
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $expedientesTotal = $em->getRepository('DGPlusbelleBundle:Paciente')->findAll();
        
        $paciente['draw']=$draw++;  
        $paciente['recordsTotal'] = count($expedientesTotal);
        $paciente['recordsFiltered']= 0;
        $paciente['data']= array();
        
        $arrayFiltro = explode(' ',$busqueda['value']);
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){
            $dql = "SELECT tip.nombre as tipo, suc.nombre as sucursal, co.costoConsulta as costo, tra.nombre as tratamiento, DATE_FORMAT(co.fechaConsulta,'%d-%m-%Y') as fechaConsulta, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link "
                    . "FROM DGPlusbelleBundle:HistorialEstetica his "
                    . "JOIN his.consulta co "
                    . "JOIN co.sucursal suc "
                    . "JOIN co.tratamiento tra "
                    . "JOIN co.tipoConsulta tip "
                    //. "JOIN co.paciente pac "
                    //. "JOIN pac.persona per "
                    . "JOIN his.detalleEstetica opc ";
                    //. "WHERE CONCAT(upper(per.nombres),upper(per.apellidos)) LIKE upper(:busqueda) "
                    //. "ORDER BY per.nombres ASC ";
            $paciente['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->getResult();

            $paciente['recordsFiltered']= count($paciente['data']);

//            $dql = "SELECT exp.numero as expediente, pac.id as id,per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link FROM DGPlusbelleBundle:Paciente pac "
//                . "JOIN pac.persona per "
//                . "JOIN pac.expediente exp "
//                . "WHERE CONCAT(upper(per.nombres),upper(per.apellidos)) LIKE upper(:busqueda) "
//                . "ORDER BY per.nombres ASC ";
//            $paciente['data'] = $em->createQuery($dql)
//                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
//                    ->setFirstResult($start)
//                    ->setMaxResults($longitud)
//                    ->getResult();
        }
        else{
//            $dql = "SELECT exp.numero as expediente, pac.id as id,per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link FROM DGPlusbelleBundle:Paciente pac "
//                . "JOIN pac.persona per JOIN pac.expediente exp ORDER BY per.nombres ASC ";
//            $paciente['data'] = $em->createQuery($dql)
//                    ->setFirstResult($start)
//                    ->setMaxResults($longitud)
//                    ->getResult();
            $dql = "SELECT tip.nombre, suc.nombre, co.costoConsulta as costo, tra.nombre as tratamiento, DATE_FORMAT(co.fechaConsulta,'%d-%m-%Y') as fechaConsulta, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link "
                    . "FROM DGPlusbelleBundle:HistorialEstetica his "
                    . "INNER JOIN his.consulta co "
                    . "INNER JOIN co.sucursal suc "
                    . "INNER JOIN co.tratamiento tra "
                    . "INNER JOIN co.tipoConsulta tip "
                    . "INNER JOIN co.paciente pac "
                    . "INNER JOIN pac.persona per "
                    . "INNER JOIN his.detalleEstetica opc "
                    . "INNER JOIN opc.detalleEstetica det "
                    . "INNER JOIN det.estetica est "
                    . "ORDER BY per.nombres ASC ";
            
            $paciente['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
            
            $paciente['recordsFiltered']= count($paciente['data']);
        }
        
        return new Response(json_encode($paciente));
    }    
}
