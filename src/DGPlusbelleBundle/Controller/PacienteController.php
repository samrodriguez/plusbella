<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        
        $sql = "select TRIM(per.nombres) as pnombre, TRIM(per.apellidos) as papellido,  "
                . "per.direccion as direccion, per.telefono as tel, per.email as email, pac.id as idpac, pac.dui as dui, pac.estado_civil as ecivil, pac.sexo as sexo, pac.ocupacion as ocupacion, "
                . "pac.lugar_trabajo as lugarTrabajo, pac.fecha_nacimiento as fechaNacimiento, pac.referido_por as referidoPor "
                . "from paciente pac inner join persona per on pac.persona = per.id order by per.nombres ASC, per.apellidos ASC";
        
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
            $entity->setFechaRegistro(new \DateTime('now'));
            
            $em->persist($entity);
            $this->generarExpediente($entity);
            $em->flush();

            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se registro un nuevo paciente",$usuario->getId());
            
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

            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se actualizo informacion de un paciente",$usuario->getId());
            
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

        
        $search  = array('Á', 'É', 'Í', 'Ó', 'Ú');
        $replace = array('A', 'E', 'I', 'O', 'U');
        
        $apellido = str_replace($search,$replace , $apellido);
        $nombre = str_replace($search,$replace , $nombre);
        
        //Generacion del numero de expediente
//        $numeroExp = $nombre[0].$apellido[0].date("Y");
        $numeroExp = substr($nombre, 0,1).substr($apellido, 0,1).date("Y");

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
        $em->flush();
    }
    
    
    /**
     * @Route("/infopaciente/get/{id}", name="get_infopaciente", options={"expose"=true})
     * @Method("GET")
     */
    public function getInfoPacienteAction(Request $request, $id) {
        
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getEntityManager();
        $id = substr($id, 1);
        
        $dql = "SELECT "
                . "per.nombres,per.apellidos,per.telefono,per.email,p.dui,p.estadoCivil,p.sexo,p.ocupacion,p.lugarTrabajo, p.fechaNacimiento, p.referidoPor, p.personaEmergencia,p.telefonoEmergencia "
                . "FROM DGPlusbelleBundle:Paciente p JOIN p.persona per WHERE per.id=p.persona AND p.id =:id";

        $paciente['regs'] = $em->createQuery($dql)
                   ->setParameter('id',$id)
                   ->getResult();
        
        //$paciente['regs'] = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        $fecha = $paciente['regs'][0]['fechaNacimiento'];
        if($fecha!=null){
            $paciente['regs'][0]['fechaNacimiento'] = $fecha->format("d-m-Y");
        }
        
        //var_dump($fecha->format("d-m-Y"));
        //var_dump($paciente);
        
        
        
        
        
        //var_dump($cita['regs'][0]["primerNombre"]);
        //var_dump($cita);
        
        return new Response(json_encode($paciente));
    }
    
    
    
    /**
     * 
     *
     * @Route("/paciente/data", name="admin_paciente_data")
     */
    public function datapacienteAction(Request $request)
    {
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
        $entity = new Paciente();
        $form = $this->createCreateForm($entity);
     
//	
        //echo $output;
	//return new Response(json_encode( $output ));
    //echo json_encode( $output );
//    return array(
//            //'entities' => $entities,
//            'pacientes' => $output['aaData'],
//            'entity' => $entity,
//            'form'   => $form->createView(),
//        );
//       // $persona = new Persona();
//        //var_dump($request);
//        $entity = new Paciente();    
//        var_dump($request);
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $expedientesTotal = $em->getRepository('DGPlusbelleBundle:Paciente')->findAll();
        
        $paciente['draw']=$draw++;  
        $paciente['recordsTotal'] = count($expedientesTotal);
        $paciente['recordsFiltered']= count($expedientesTotal);
        $paciente['data']= array();
        //var_dump($busqueda);
        //die();
        $arrayFiltro = explode(' ',$busqueda['value']);
        
        //echo count($arrayFiltro);
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){
            //foreach ($arrayFiltro as $row){
                //var_dump($row);
              //  if($row!=''){
                    
                    $dql = "SELECT exp.numero as expediente, pac.id as id,per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link FROM DGPlusbelleBundle:Paciente pac "
                        . "JOIN pac.persona per "
                        . "JOIN pac.expediente exp "
                        . "WHERE CONCAT(upper(per.nombres),upper(per.apellidos)) LIKE upper(:busqueda) "
                        . "ORDER BY per.nombres ASC ";
                    $paciente['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->getResult();
                    
                    $paciente['recordsFiltered']= count($paciente['data']);
                    
                    $dql = "SELECT exp.numero as expediente, pac.id as id,per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link FROM DGPlusbelleBundle:Paciente pac "
                        . "JOIN pac.persona per "
                        . "JOIN pac.expediente exp "
                        . "WHERE CONCAT(upper(per.nombres),upper(per.apellidos)) LIKE upper(:busqueda) "
                        . "ORDER BY per.nombres ASC ";
                    $paciente['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->setFirstResult($start)
                            ->setMaxResults($longitud)
                            ->getResult();
              //  }
            //}
                    //var_dump($paciente);

    //                if($paciente['data']==null)
    //                    $paciente['data']=$pacientePrimeraBusqueda;
    //                else
                    //array_push($paciente['data'], $paciente['data']);
//                }
//            }
            
            
            //var_dump($paciente['data']);
        }
        else{
            $dql = "SELECT exp.numero as expediente, pac.id as id,per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link FROM DGPlusbelleBundle:Paciente pac "
                . "JOIN pac.persona per JOIN pac.expediente exp ORDER BY per.nombres ASC ";
            $paciente['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        //$longitud = $request->query->get('length');
        //var_dump($start);
        
        //var_dump(count($pacientesTotal));
        
        //$array = array("draw"=>23);
//        $paciente['draw']=23;
//        $paciente['recordsTotal']=57;
//        $paciente['recordsFiltered']=57;
        
        
        return new Response(json_encode($paciente));
    }
    
    /**
    * Ajax utilizado para buscar informacion de una consulta de estetica
    *  
    * @Route("/busqueda-paciente-select/data", name="busqueda_paciente_select")
    */
    public function busquedaPacienteAction(Request $request)
    {
        $busqueda = $request->query->get('q');
        $page = $request->query->get('page');
        
        //var_dump($page);
        
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT pac.id pacienteid, per.nombres, per.apellidos "
                        . "FROM DGPlusbelleBundle:Paciente pac "
                        . "JOIN pac.persona per "
                        . "WHERE CONCAT(upper(per.nombres), ' ', upper(per.apellidos)) LIKE upper(:busqueda) "
                        . "ORDER BY per.nombres ASC ";
        
        $paciente['data'] = $em->createQuery($dql)
                ->setParameters(array('busqueda'=>"%".$busqueda."%"))
                ->setMaxResults( 10 )
                ->getResult();
        
        return new Response(json_encode($paciente));
    }
    
    
    
    /**
     * 
     *
     * @Route("/paciente/data/exp", name="admin_paciente_expediente")
     */
    public function datapacienteexpAction(Request $request)
    {
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
        $entity = new Paciente();
        $form = $this->createCreateForm($entity);
     

        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $expedientesTotal = $em->getRepository('DGPlusbelleBundle:Expediente')->findAll();
        
        $paciente['draw']=$draw++;  
        $paciente['recordsTotal'] = count($expedientesTotal);
        $paciente['recordsFiltered']= count($expedientesTotal);
        $paciente['data']= array();
        //var_dump($busqueda);
        //die();
        $arrayFiltro = explode(' ',$busqueda['value']);
        
        //echo count($arrayFiltro);
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){
                                
                    $dql = "SELECT exp.numero as expediente, pac.id as id,per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, '<a><i style=\"cursor:pointer;\" data-toggle=\"tooltip\" data-original-title=\"Atrás\" class=\"infoPaciente fa fa-info-circle\"></i></a><a style=\"position:absolute; margin-left:10px;\"><i style=\"cursor:pointer;\"  class=\"expPaciente fa fa-list\"></i></a>'  as link FROM DGPlusbelleBundle:Paciente pac "
                        . "JOIN pac.persona per "
                        . "JOIN pac.expediente exp "
                        . "WHERE CONCAT(CONCAT(upper(per.nombres),upper(per.apellidos)),exp.numero) LIKE upper(:busqueda) "
                        . "ORDER BY per.nombres ASC ";
                    $paciente['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->getResult();
                    
                    $paciente['recordsFiltered']= count($paciente['data']);
                    
                    $dql = "SELECT exp.numero as expediente, pac.id as id,per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, '<a><i style=\"cursor:pointer;\" data-toggle=\"tooltip\" data-original-title=\"Atrás\" class=\"infoPaciente fa fa-info-circle\"></i></a><a style=\"position:absolute; margin-left:10px;\"><i style=\"cursor:pointer;\"  class=\"expPaciente fa fa-list\"></i></a>'  as link FROM DGPlusbelleBundle:Paciente pac "
                        . "JOIN pac.persona per "
                        . "JOIN pac.expediente exp "
                        . "WHERE CONCAT(CONCAT(upper(per.nombres),upper(per.apellidos)),exp.numero) LIKE upper(:busqueda) "
                        . "ORDER BY per.nombres ASC ";
                    $paciente['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->setFirstResult($start)
                            ->setMaxResults($longitud)
                            ->getResult();
        }
        else{
            $dql = "SELECT exp.numero as expediente, pac.id as id,per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, '<a><i style=\"cursor:pointer;\" data-toggle=\"tooltip\" data-original-title=\"Atrás\" class=\"infoPaciente fa fa-info-circle\"></i></a><a style=\"position:absolute; margin-left:10px;\"><i style=\"cursor:pointer;\"  class=\"expPaciente fa fa-list\"></i></a>'  as link FROM DGPlusbelleBundle:Paciente pac "
                . "JOIN pac.persona per JOIN pac.expediente exp ORDER BY per.nombres ASC ";
            $paciente['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
            $paciente['recordsTotal'] = count($paciente['data']);
        }
        //$longitud = $request->query->get('length');
        //var_dump($start);
        
//        var_dump(count($paciente['recordsTotal']));
//        die();
        
//        $paciente['recordsFiltered']= count($paciente['data']);
        //var_dump(count($pacientesTotal));
        
        //$array = array("draw"=>23);
//        $paciente['draw']=23;
//        $paciente['recordsTotal']=57;
//        $paciente['recordsFiltered']=57;
        
        
        return new Response(json_encode($paciente));
    }
}
