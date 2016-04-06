<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $em = $this->getDoctrine()->getEntityManager();
        $rsm = new ResultSetMapping();
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $sql = "SELECT historial, costo, fechaConsulta, tratamiento, tipo, paciente, estetica, sucursal, link
                    FROM ( SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%d/%m/%Y') as fechaConsulta, 
                                    tra.nombre as tratamiento, 
                                    suc.nombre as sucursal, 
                                    tip.nombre as tipo, 
                                    CONCAT(per.nombres, ' ', per.apellidos) as paciente,
                                    '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-file-pdf-o\"></i></a>' as link,
                                    'Corporal' as estetica
                            FROM composicion_corporal his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id                   
                        union distinct
                            SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%d/%m/%Y') as fechaConsulta, 
                                    tra.nombre as tratamiento, 
                                    suc.nombre as sucursal, 
                                    tip.nombre as tipo, 
                                    CONCAT(per.nombres, ' ', per.apellidos) as paciente,
                                    '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-file-pdf-o\"></i></a>' as link,
                                    'Facial' as estetica
                            FROM historial_estetica his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id
                        union distinct
                            SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%d/%m/%Y') as fechaConsulta, 
                                    tra.nombre as tratamiento, 
                                    suc.nombre as sucursal, 
                                    tip.nombre as tipo, 
                                    CONCAT(per.nombres, ' ', per.apellidos) as paciente,
                                    '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-file-pdf-o\"></i></a>' as link,
                                    'Botox' as estetica
                            FROM consulta_botox his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id) a      
                    GROUP BY historial
                    ORDER BY fechaConsulta desc, estetica ";
            
        $rsm->addScalarResult('historial','historial');
        $rsm->addScalarResult('costo','costo');
        $rsm->addScalarResult('fechaConsulta','fechaConsulta');
        $rsm->addScalarResult('tratamiento','tratamiento');
        $rsm->addScalarResult('sucursal','sucursal');
        $rsm->addScalarResult('tipo','tipo');
        $rsm->addScalarResult('estetica','estetica');
        $rsm->addScalarResult('paciente','paciente');
        $rsm->addScalarResult('link','link');
            
        $expedientesTotal = $em->createNativeQuery($sql, $rsm)
                                   ->getResult();
        
        //$expedientesTotal = $em->getRepository('DGPlusbelleBundle:Paciente')->findAll();
        $paciente['draw']=$draw++;  
        $paciente['recordsTotal'] = count($expedientesTotal);
        $paciente['recordsFiltered']= 0;
        $paciente['data']= array();
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){
            $sql = "SELECT historial, costo, fechaConsulta, tratamiento, tipo, paciente, estetica, sucursal, link
                    FROM ( SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%d/%m/%Y') as fechaConsulta, 
                                    tra.nombre as tratamiento, 
                                    suc.nombre as sucursal, 
                                    tip.nombre as tipo, 
                                    CONCAT(per.nombres, ' ', per.apellidos) as paciente,
                                    CONCAT('<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-file-pdf-o\" id=\"', co.id, '\" ></i></a>') as link,
                                    'Corporal' as estetica
                            FROM composicion_corporal his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id 
                                WHERE CONCAT(upper(per.nombres),upper(per.apellidos)) LIKE upper(:busqueda)
                        union distinct
                            SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%d/%m/%Y') as fechaConsulta, 
                                    tra.nombre as tratamiento, 
                                    suc.nombre as sucursal, 
                                    tip.nombre as tipo, 
                                    CONCAT(per.nombres, ' ', per.apellidos) as paciente,
                                    CONCAT('<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-file-pdf-o\" id=\"', co.id, '\" ></i></a>') as link,
                                    'Facial' as estetica
                            FROM historial_estetica his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id
                                WHERE CONCAT(upper(per.nombres),upper(per.apellidos)) LIKE upper(:busqueda)
                        union distinct     
                            SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%d/%m/%Y') as fechaConsulta, 
                                    tra.nombre as tratamiento, 
                                    suc.nombre as sucursal, 
                                    tip.nombre as tipo, 
                                    CONCAT(per.nombres, ' ', per.apellidos) as paciente,
                                    '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-file-pdf-o\"></i></a>' as link,
                                    'Botox' as estetica
                            FROM consulta_botox his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id  
                                WHERE CONCAT(upper(per.nombres),upper(per.apellidos)) LIKE upper(:busqueda)) a
                    GROUP BY historial
                    ORDER BY fechaConsulta desc, estetica ";
            
            $rsm->addScalarResult('historial','historial');
            $rsm->addScalarResult('costo','costo');
            $rsm->addScalarResult('fechaConsulta','fechaConsulta');
            $rsm->addScalarResult('tratamiento','tratamiento');
            $rsm->addScalarResult('sucursal','sucursal');
            $rsm->addScalarResult('tipo','tipo');
            $rsm->addScalarResult('estetica','estetica');
            $rsm->addScalarResult('paciente','paciente');
            $rsm->addScalarResult('link','link');
            
            $paciente['data'] = $em->createNativeQuery($sql, $rsm)
                                   ->setParameter('busqueda', "%".$busqueda['value']."%")
                                   ->getResult();
            
            $paciente['recordsFiltered']= count($paciente['data']);
        }
        else{
            $sql = "SELECT historial, costo, fechaConsulta, tratamiento, tipo, paciente, estetica, sucursal, link
                    FROM ( SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%d/%m/%Y') as fechaConsulta, 
                                    tra.nombre as tratamiento, 
                                    suc.nombre as sucursal, 
                                    tip.nombre as tipo, 
                                    CONCAT(per.nombres, ' ', per.apellidos) as paciente,
                                    CONCAT('<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-file-pdf-o\" id=\"', co.id, '\" ></i></a>') as link,
                                    'Corporal' as estetica
                            FROM composicion_corporal his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id                   
                        union distinct
                            SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%d/%m/%Y') as fechaConsulta, 
                                    tra.nombre as tratamiento, 
                                    suc.nombre as sucursal, 
                                    tip.nombre as tipo, 
                                    CONCAT(per.nombres, ' ', per.apellidos) as paciente,
                                    CONCAT('<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-file-pdf-o\" id=\"', co.id, '\" ></i></a>') as link,
                                    'Facial' as estetica
                            FROM historial_estetica his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id
                        union distinct     
                            SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%d/%m/%Y') as fechaConsulta, 
                                    tra.nombre as tratamiento, 
                                    suc.nombre as sucursal, 
                                    tip.nombre as tipo, 
                                    CONCAT(per.nombres, ' ', per.apellidos) as paciente,
                                    '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-file-pdf-o\"></i></a>' as link,
                                    'Botox' as estetica
                            FROM consulta_botox his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id) a        
                    GROUP BY historial
                    ORDER BY fechaConsulta desc, estetica ";
            
            $rsm->addScalarResult('historial','historial');
            $rsm->addScalarResult('costo','costo');
            $rsm->addScalarResult('fechaConsulta','fechaConsulta');
            $rsm->addScalarResult('tratamiento','tratamiento');
            $rsm->addScalarResult('sucursal','sucursal');
            $rsm->addScalarResult('tipo','tipo');
            $rsm->addScalarResult('estetica','estetica');
            $rsm->addScalarResult('paciente','paciente');
            $rsm->addScalarResult('link','link');
            
            $paciente['data'] = $em->createNativeQuery($sql, $rsm)
                                   ->getResult();
            
            $paciente['recordsFiltered']= count($paciente['data']);
        }
        
        return new Response(json_encode($paciente));
    }    
    
    /**
    * Ajax utilizado para buscar informacion de una consulta de estetica
    *  
    * @Route("/busqueda-estetica-pdf", name="busqueda_estetica_pdf")
    */
    public function busquedaEsteticaPDFAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $em = $this->getDoctrine()->getEntityManager();
            $rsm = new ResultSetMapping();
            $consultaId = $this->get('request')->request->get('idcon');
            
            $sql = "SELECT historial, costo, fechaConsulta, tratamiento, tipo, paciente, estetica, sucursal
                    FROM ( SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%m/%d/%Y') as fechaConsulta, 
                                    tra.id as tratamiento, 
                                    suc.id as sucursal, 
                                    tip.id as tipo, 
                                    pac.id as paciente,
                                    'Corporal' as estetica
                            FROM composicion_corporal his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id 
                                WHERE co.id = :busqueda
                        union distinct
                            SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%m/%d/%Y') as fechaConsulta, 
                                    tra.id as tratamiento, 
                                    suc.id as sucursal, 
                                    tip.id as tipo, 
                                    pac.id as paciente,
                                    'Facial' as estetica
                            FROM historial_estetica his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id
                                WHERE co.id = :busqueda
                        union distinct        
                            SELECT distinct co.id as historial, ROUND(co.costo_consulta, 2) as costo,  
                                    DATE_FORMAT(co.fecha_consulta,'%m/%d/%Y') as fechaConsulta, 
                                    tra.id as tratamiento, 
                                    suc.id as sucursal, 
                                    tip.id as tipo, 
                                    pac.id as paciente,
                                    'Botox' as estetica
                            FROM consulta_botox his
                                INNER JOIN consulta co on his.consulta = co.id
                                INNER JOIN paciente pac on co.paciente = pac.id
                                INNER JOIN persona per on pac.persona = per.id
                                INNER JOIN tratamiento tra on co.tratamiento_id = tra.id
                                INNER JOIN sucursal suc on co.sucursal = suc.id
                                INNER JOIN tipo_consulta tip on co.tipo_consulta = tip.id 
                                WHERE co.id = :busqueda) a
                    GROUP BY historial
                    ORDER BY fechaConsulta desc, estetica ";
            
            $rsm->addScalarResult('historial','historial');
            $rsm->addScalarResult('costo','costo');
            $rsm->addScalarResult('fechaConsulta','fechaConsulta');
            $rsm->addScalarResult('tratamiento','tratamiento');
            $rsm->addScalarResult('sucursal','sucursal');
            $rsm->addScalarResult('tipo','tipo');
            $rsm->addScalarResult('estetica','estetica');
            $rsm->addScalarResult('paciente','paciente');
            
            $estetica = $em->createNativeQuery($sql, $rsm)
                                   ->setParameter('busqueda', $consultaId)
                                   ->getSingleResult();
            
            $sql3 = "SELECT opc.id as historial
                     FROM historial_estetica his
                        INNER JOIN opciones_detalle_estetica opc on his.detalle_estetica = opc.id
                     WHERE his.consulta = :busqueda ";
            
            $rsm3 = new ResultSetMapping();
            $rsm3->addScalarResult('historial','historial');
            
            
            $historial = $em->createNativeQuery($sql3, $rsm3)
                                   ->setParameter('busqueda', $consultaId)
                                   ->getResult();
            
            $valores = array();
            
            foreach ($historial as $key => $value) {
                array_push($valores, $value['historial']);
            }
            
            $sql2 = "SELECT his.id as historial,
                            his.peso as peso,
                            his.grasa_corporal  as grasa_corporal,
                            his.agua_corporal as agua_corporal,
                            his.fecha as fechaConsulta,
                            his.masa_musculo as masa_musculo,
                            his.valoracion_fisica as valoracion_fisica,
                            his.edad_metabolica as edad_metabolica,
                            his.dci_bmr as dci_bmr,
                            his.masa_osea as masa_osea,
                            his.grasa_visceral as grasa_visceral
                     FROM composicion_corporal his
                        INNER JOIN consulta co on his.consulta = co.id
                     WHERE co.id = :busqueda ";
            
            $rsm2 = new ResultSetMapping();
            $rsm2->addScalarResult('historial','historial');
            $rsm2->addScalarResult('peso','peso');
            $rsm2->addScalarResult('grasa_corporal','grasa_corporal');
            $rsm2->addScalarResult('agua_corporal','agua_corporal');
            $rsm2->addScalarResult('fechaConsulta','fechaConsulta');
            $rsm2->addScalarResult('masa_musculo','masa_musculo');
            $rsm2->addScalarResult('valoracion_fisica','valoracion_fisica');
            $rsm2->addScalarResult('edad_metabolica','edad_metabolica');
            $rsm2->addScalarResult('dci_bmr','dci_bmr');
            $rsm2->addScalarResult('masa_osea','masa_osea');
            $rsm2->addScalarResult('grasa_visceral','grasa_visceral');
            
            $corporal = $em->createNativeQuery($sql2, $rsm2)
                                   ->setParameter('busqueda', $consultaId)
                                   ->getResult();
            
            $response = new JsonResponse();
            $response->setData(array(
                                'data' => $estetica,
                                'corporal' => $corporal,
                                'valores' => $valores
                            )); 
            
            return $response; 
        } else {    
            return new Response('0');              
        }  
    }
}
