<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Paciente;
use DGPlusbelleBundle\Entity\Signos;
use DGPlusbelleBundle\Entity\Consulta;
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
        $numeroExp = "EX";

        $dql = "SELECT COUNT(exp)+1 FROM DGPlusbelleBundle:Expediente exp WHERE exp.numero LIKE :numero";

        $num = $em->createQuery($dql)
                   ->setParameter('numero','%'.$numeroExp.'%')
                   ->getResult();
        //var_dump($user);
        $numString = $num[0]["1"];
        //var_dump($numString);

        switch(strlen($numString)){
            case 1:
                    $numeroExp .= "0000".$numString;
                break;
            case 2:
                    $numeroExp .= "000".$numString;
                break;
            case 3:
                    $numeroExp .= "00".$numString;
                break;
            case 4:
                    $numeroExp .= "0".$numString;
                break;
            case 5:
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
    * Ajax utilizado para buscar informacion del producto
    *  
    * @Route("/registro/datos_generales/set", name="admin_registro_datos_generales_paciente")
    */
    public function registrarDatosGeneralesPacienteAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $em = $this->getDoctrine()->getManager();
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            
            $id = $this->get('request')->request->get('id');
            $nombres = $this->get('request')->request->get('nombres');
            $apellidos = $this->get('request')->request->get('apellidos');
            $telefono = $this->get('request')->request->get('telefono');
            $telefono2 = $this->get('request')->request->get('telefono2');
            $direccion = $this->get('request')->request->get('direccion');
            $correo = $this->get('request')->request->get('correo');
            $dui = $this->get('request')->request->get('dui');
            $ecivil = $this->get('request')->request->get('ecivil');
            $sexo = $this->get('request')->request->get('sexo');
            $ocupacion = $this->get('request')->request->get('ocupacion');
            $lugarTrabajo = $this->get('request')->request->get('lugarTrabajo');
            $fechaNacimiento = $this->get('request')->request->get('fechaNacimiento');
            $referidoPor = $this->get('request')->request->get('referidoPor');
            $personaEmergencia = $this->get('request')->request->get('personaEmergencia');
            $telefonoEmergencia = $this->get('request')->request->get('telefonoEmergencia');
            
            $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
            $persona = $em->getRepository('DGPlusbelleBundle:Persona')->find($paciente->getPersona()->getId());
            
            $persona->setNombres($nombres);
            $persona->setApellidos($apellidos);
            
            if($telefono != ''){
                $persona->setTelefono($telefono);
            }
            
            if($direccion != ''){
                $persona->setDireccion($direccion);
            }
            
            if($telefono2 != ''){
                $persona->setTelefono2($telefono2);
            }
            
            if($correo != ''){
                $persona->setEmail($correo);
            }
            
            $em->merge($persona);
            $em->flush();
            
            if($dui != ''){
                $paciente->setDui($dui);
            }
            
            if($ecivil != ''){
                $paciente->setEstadoCivil($ecivil);
            }
            
            if($sexo != ''){
                $paciente->setSexo($sexo);
            }
            
            if($ocupacion != ''){
                $paciente->setOcupacion($ocupacion);
            }
            
            if($lugarTrabajo != ''){
                $paciente->setLugarTrabajo($lugarTrabajo);
            }
            
            if($referidoPor != ''){
                $paciente->setReferidoPor($referidoPor);
            }
            
            if($fechaNacimiento != ''){
                $fecha = explode('-', $fechaNacimiento);
                $paciente->setFechaNacimiento(new \DateTime($fecha[0].'-'.$fecha[1].'-'.$fecha[2]));
            }
                
            if($personaEmergencia != ''){
                $paciente->setPersonaEmergencia($personaEmergencia);
                
                if($telefonoEmergencia != ''){
                    $paciente->setTelefonoEmergencia($telefonoEmergencia);
                }
            }
            
            $em->merge($paciente);
            $em->flush();
            
            $this->get('bitacora')->escribirbitacora("Se edito la informacion general del paciente", $usuario->getId());
            
            $response = new JsonResponse();
            $response->setData(array(
                                'exito' => '1'
                            )); 
            
            return $response; 
        } else {    
            return new Response('0');              
        } 
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
           //var_dump($paciente);
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
        $paciente['recordsTotal'] = 0;
        $paciente['recordsFiltered']= 0;
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
                    
                    $dql = "SELECT CONCAT('<a class=\"link_expediente\" id=\"',exp.numero,'\">',exp.numero,'</a>') as expediente, pac.id as id,per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, CONCAT('<a ><i id=\"',pac.id,'\" style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>') as link FROM DGPlusbelleBundle:Paciente pac "
                        . "JOIN pac.persona per "
                        . "JOIN pac.expediente exp "
                        . "WHERE CONCAT(upper(per.nombres),upper(per.apellidos)) LIKE upper(:busqueda) "
                        . "ORDER BY per.nombres ASC ";
                    $paciente['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->getResult();
                    
                    $paciente['recordsFiltered']= count($paciente['data']);
                    $paciente['recordsTotal'] = count($paciente['data']);
                    $dql = "SELECT CONCAT('<a class=\"link_expediente\" id=\"',exp.numero,'\">',exp.numero,'</a>') as expediente, pac.id as id,per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, CONCAT('<a ><i id=\"',pac.id,'\" style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>') as link FROM DGPlusbelleBundle:Paciente pac "
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
            $dql = "SELECT CONCAT('<a class=\"link_expediente\" id=\"',exp.numero,'\">',exp.numero,'</a>') as expediente, per.nombres, per.apellidos, DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, CONCAT('<a ><i id=\"',pac.id,'\" style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>') as link FROM DGPlusbelleBundle:Paciente pac "
                . "JOIN pac.persona per JOIN pac.expediente exp ORDER BY per.nombres ASC ";
            $paciente['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
            $paciente['recordsFiltered']= count($paciente['data']);
            $paciente['recordsTotal'] = count($paciente['data']);
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
        $expedientesTotal = 0;/*$em->getRepository('DGPlusbelleBundle:Expediente')->findAll();*/
        
        $paciente['draw']=$draw++;  
        //$paciente['recordsTotal'] = count($expedientesTotal);
        //$paciente['recordsFiltered']= count($expedientesTotal);
        $paciente['data']= array();
        //var_dump($busqueda);
        //die();
        $arrayFiltro = explode(' ',$busqueda['value']);
        //var_dump($longitud);
        //echo count($arrayFiltro);
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        //var_dump($busqueda['value']);
        if($busqueda['value']!=''){
                                
//                    $dql = "SELECT exp.numero as expediente, per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, CONCAT('<a id=\"',pac.id,'\"><i style=\"cursor:pointer;\" data-toggle=\"tooltip\" data-original-title=\"Atrás\" class=\"infoPaciente fa fa-info-circle\"></i></a>') as link FROM DGPlusbelleBundle:Paciente pac "
//                        . "JOIN pac.persona per "
//                        . "JOIN pac.expediente exp "
//                        . "WHERE CONCAT(CONCAT(upper(per.nombres),upper(per.apellidos)),exp.numero) LIKE upper(:busqueda) OR exp.numero LIKE (:busqueda) "
//                        . "ORDER BY per.nombres ASC ";
//                    $paciente['data'] = $em->createQuery($dql)
//                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
//                            ->getResult();
                    
//            $dql = "SELECT exp.numero as expediente, per.nombres,per.apellidos,DATE_FORMAT(pac.fechaNacimiento,'%d-%m-%Y') as fechaNacimiento, CONCAT('<a id=\"',pac.id,'\"><i style=\"cursor:pointer;\" data-toggle=\"tooltip\" data-original-title=\"Atrás\" class=\"infoPaciente fa fa-info-circle\"></i></a>') as link FROM DGPlusbelleBundle:Paciente pac "
//                . "JOIN pac.persona per JOIN pac.expediente exp ORDER BY per.nombres ASC ";
            $sql = "SELECT count(*) as total FROM listadoexpediente WHERE expediente='".strtoupper($busqueda['value'])."' ORDER BY fecha DESC";
        
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $paciente['data'] = $stmt->fetchAll();
//            var_dump($paciente['data'][0]['total']);
            $paciente['recordsTotal'] = $paciente['data'][0]['total'];
            $paciente['recordsFiltered']= $paciente['data'][0]['total'];
            
                    
//            $sql = "SELECT fecha as fecha,transaccion,atendido,realizado, 
//                CASE
//                WHEN atendido = 'JUAN CARLOS PACHECO CARDONA' THEN CONCAT('<a href=\"newconpacienteSD?id=P',id,'&idtransaccion=',idtransaccion,'\">', 'Ver detalles</a>')
//                WHEN atendido = 'MILDRED LARA DE PACHECO' THEN CONCAT('<a href=\"newconpacienteLPB?id=P',id,'&idtransaccion=',idtransaccion,'\">', 'Ver detalles</a>')
//                WHEN transaccion = 'Venta paquete' THEN CONCAT('<a href=\"historialconsulta?id=P',id,'\">', 'Ver detalles</a>')
//                ELSE CONCAT('<a href=\"historialconsulta?id=P',id,'\">', 'Ver detalles</a>')
//                
//                END AS detalles FROM listadoexpediente WHERE expediente='".strtoupper($busqueda['value'])."' ORDER BY fecha DESC LIMIT ".$start.",".$longitud;
            
            $sql = "SELECT fecha as fecha,transaccion,atendido,realizado, 
                CASE
                WHEN atendido = 'JUAN CARLOS PACHECO CARDONA' THEN CONCAT('<a id=\"',idtransaccion,'\" class=\"link_ SD\">', 'Ver detalles</a>')
                WHEN atendido = 'MILDRED LARA DE PACHECO' THEN CONCAT('<a id=\"',idtransaccion,'\" class=\"link_ LPB\">', 'Ver detalles</a>')
                WHEN transaccion = 'Venta paquete' THEN CONCAT('<a id=\"',idtransaccion,'\" class=\"link_ paquete\">', 'Ver detalles</a>')
                ELSE CONCAT('<a id=\"',idtransaccion,'\" class=\"link_ tratamiento\">', 'Ver detalles</a>')
                
                END AS detalles FROM listadoexpediente WHERE expediente='".strtoupper($busqueda['value'])."' ORDER BY fecha DESC LIMIT ".$start.",".$longitud;
        
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $paciente['data'] = $stmt->fetchAll();
            //$paciente['recordsTotal'] = count($paciente['data']);
            
            

            
            
        }
        else{ 
//            $sql = "SELECT * FROM listadoexpediente ORDER BY fecha DESC LIMIT ".$start.",".$longitud;
//        
//            $em = $this->getDoctrine()->getManager();
//            $stmt = $em->getConnection()->prepare($sql);
//            $stmt->execute();
            $paciente['draw']=$draw++;  
            $paciente['recordsTotal'] = 0;
            $paciente['recordsFiltered']= 0;
            
            $paciente['data'] = array();
//            $paciente['recordsTotal'] = count($paciente['data']);
        }
        //var_dump($paciente);
        
        
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * 
     *
     * @Route("/busqueda/expediente_data/registros", name="busqueda_expediente_data")
     */
    public function databusquedaAction(Request $request)
    {
        
        
        
        $busqueda = $request->get('expediente');
//        $inicioRegistro = ($paginaActual*10)-10;
        //var_dump($busqueda);
        
        $response = new JsonResponse();
//        $start = $request->query->get('start');
//        $draw = $request->query->get('draw');
//        $longitud = $request->query->get('length');
//        $busqueda = $request->query->get('search');
        //var_dump($inicioRegistro);
        //var_dump($longitud);
        
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT pac.id,pac.ocupacion,per.direccion,per.telefono,per.nombres as nombres,per.apellidos as apellidos, DATE_FORMAT(pac.fechaNacimiento,'%Y-%m-%d') as fechaNacimiento, per.direccion, "
                        . " CONCAT('<a href=\"\">','Ver','</a>') as detalles "
                        . "FROM DGPlusbelleBundle:Expediente exp "
                        . "JOIN exp.paciente pac "
                        . "JOIN pac.persona per "
                        . "WHERE exp.numero=:busqueda";
        
        $paciente['data'] = $em->createQuery($dql)
                ->setParameters(array('busqueda'=>strtoupper($busqueda))) 
                ->getResult();
        
        //var_dump($paciente);
        //$paciente['edad']=$paciente['data']['fechaNacimiento'];    
        
        //var_dump($paciente['data']);
        
        if(count($paciente['data'])!=0){
            if($paciente['data'][0]['fechaNacimiento']!=''){
                    $fecha = $paciente['data'][0]['fechaNacimiento'];

                    //Calculo de la edad
                    list($Y,$m,$d) = explode("-",$fecha);
                    $edad = date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y;       
                    $edad = $edad. " años";
            }
            else{
                $edad = "No se ha ingresado fecha de nacimiento";
            }
            $paciente['edad'] = $edad;
        }
        else{
            $paciente['edad'] = 0;
        }
        
                //var_dump(count($paciente['data']));
        //die();
        //$data = new \stdClass();
        
        if(count($paciente['data']==0)){
            //$data->estado = true;//vacio
//            $data->nombre = $paciente['data']["nombres"]. ' '. $paciente['data']["apellidos"];
//            $data->fecha = $paciente['data']["fechaNacimiento"];
//            $data->direccion = $paciente['data']["direccion"];
            
        }
        else{
            //$data->estado = false;//encontrado
//            $data->nombre = $paciente['data']["nombres"]. ' '. $paciente['data']["apellidos"];
//            $data->fecha = $paciente['data']["fechaNacimiento"];
//            $data->direccion = $paciente['data']["direccion"];
        }
        
        $response->setData($paciente);    
        return $response; 
        //return new Response(json_encode($reg));
    }
    
    
    
    /**
     * 
     *
     * @Route("/pacienteguardar/data/guardar", name="admin_paciente_guardar_ajax")
     */
    public function dataPacienteGuardarAction(Request $request)
    {
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
        

        $id = $request->get('id');
        
        
        
                    
        
        $nombres = $request->get('nombres');
        $apellidos = $request->get('apellidos');
        
        $telefono1 =$request->get('telefono1');
        $telefono2 =$request->get('telefono2');
        $direccion =$request->get('direccion');
        $correo =$request->get('correo');
        $dui =$request->get('dui');
        $estadoCivil =$request->get('estadoCivil');
        $sexo=$request->get('sexo');
        $ocupacion=$request->get('ocupacion');
        $lugarTrabajo=$request->get('lugarTrabajo');
        
        $fechaNacimiento = $request->get('fechaNacimiento');
        $referidoPor = $request->get('referidoPor');
        $llamarA = $request->get('llamarA');
        $telefonoEmergencia = $request->get('telefonoEmergencia');
        
//        var_dump($nombres);
//        var_dump($apellidos);
//        var_dump($telefono1);
//        var_dump($telefono2);
//        var_dump($direccion);
        
        
        
        //var_dump($fechaNacimiento);
        $em = $this->getDoctrine()->getEntityManager();
        
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        
        
        if(count($paciente)!=0){
            $persona = $em->getRepository('DGPlusbelleBundle:Persona')->find($paciente->getPersona()->getId());
            $persona->setNombres($nombres);
            $persona->setApellidos($apellidos);
            $persona->setTelefono($telefono1);
            $persona->setTelefono2($telefono2);
            
            $persona->setDireccion($direccion);
            $persona->setEmail($correo);
            $paciente->setDui($dui);
            $paciente->setEstadoCivil($estadoCivil);
            $paciente->setSexo($sexo);
            $paciente->setOcupacion($ocupacion);
            $paciente->setLugarTrabajo($lugarTrabajo);
            $paciente->setFechaNacimiento(new \DateTime($fechaNacimiento));
            
            $paciente->setReferidoPor($referidoPor);
            
            $paciente->setTelefonoEmergencia($telefonoEmergencia);
            
            
            
            $paciente->setPersonaEmergencia($llamarA);
            
            
            
            
            $em->merge($persona);
            $em->merge($paciente);
            $em->flush();
            return new Response(json_encode(0));
        }
        else{
            return new Response(json_encode(1));
        }
        
        
        
    }
    
    
    
    
    /**
     * 
     *
     * @Route("/pacienteguardar/data/antecedentes", name="admin_paciente_guardar_antecedentes_ajax")
     */
    public function dataPacienteGuardarAntecedentesAction(Request $request)
    {
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
        

        $id = $request->get('id');
        
        $patologicos = $request->get('patologicos');
        $familiares = $request->get('familiares');
        $alergias = $request->get('alergias');
        
        
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        
        
        if(count($paciente)!=0){
            //$persona = $em->getRepository('DGPlusbelleBundle:Persona')->find($paciente->getPersona()->getId());
            
            
            $paciente->setPatologicos($patologicos);
            $paciente->setFamiliares($familiares);
            $paciente->setAlergias($alergias);
            
            $em->merge($paciente);
            $em->flush();
            return new Response(json_encode(0));
        }
        else{
            return new Response(json_encode(1));
        }   
    }
    
    
    /**
     * 
     *
     * @Route("/pacientesignos/data/signos", name="admin_paciente_guardar_signos_ajax")
     */
    public function dataPacienteSignosAction(Request $request)
    {
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
        

        $id = $request->get('id');
        
        $peso = $request->get('peso');
        $talla = $request->get('talla');
        $frecRespiratoria = $request->get('frecRespiratoria');
        $presionDiastolica = $request->get('presionDiastolica');
        $presionSistolica = $request->get('presionSistolica');
        $temperatura = $request->get('temperatura');
        $frecCardiaca = $request->get('frecCardiaca');
        
//        $patologicos = $request->get('patologicos');
//        $familiares = $request->get('familiares');
//        $alergias = $request->get('alergias');
//        
//        $patologicos = $request->get('patologicos');
//        $familiares = $request->get('familiares');
//        $alergias = $request->get('alergias');
        
        
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        
        $signos = new Signos();
        $consulta = new Consulta();
        
        if(count($paciente)!=0){
            //$persona = $em->getRepository('DGPlusbelleBundle:Persona')->find($paciente->getPersona()->getId());
            
            $signos->setPeso($peso);
            $signos->setTalla($talla);
            $signos->setPresionSistolica($presionSistolica);
            $signos->setPresionDiastolica($presionDiastolica);
            $signos->setFrecRespiratotira($frecRespiratoria);
            $signos->setFrecCardiaca($frecCardiaca);
            $signos->setTemperatura($temperatura);
            $signos->setConsulta($consulta);
            $consulta->setHoraInicio(new \DateTime('now'));
            $consulta->setHoraFin(new \DateTime('now'));
            $consulta->setFechaConsulta(new \DateTime('now'));
            $consulta->setPaciente($paciente);
            $consulta->setReportePlantilla(1);
            $consulta->setCostoConsulta(0);
            
            $em->persist($consulta);
            $em->flush();
            $em->persist($signos);
            $em->flush();
            
            return new Response(json_encode($consulta->getId()));
        }
        else{
            return new Response(json_encode(0));
        }
        
        
        
    }
    
    
    
    
    
    
    /**
     * 
     *
     * @Route("/pacientesignos/data/signosactualizar", name="admin_paciente_actualizar_signos_ajax")
     */
    public function dataPacienteSignosActualizarAction(Request $request)
    {
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
        

        $id = $request->get('id');
        
        $peso = $request->get('peso');
        $talla = $request->get('talla');
        $frecRespiratoria = $request->get('frecRespiratoria');
        $presionDiastolica = $request->get('presionDiastolica');
        $presionSistolica = $request->get('presionSistolica');
        $temperatura = $request->get('temperatura');
        $frecCardiaca = $request->get('frecCardiaca');
        $idConsulta = $request->get('idConsulta');
        
//        $patologicos = $request->get('patologicos');
//        $familiares = $request->get('familiares');
//        $alergias = $request->get('alergias');
//        
//        $patologicos = $request->get('patologicos');
//        $familiares = $request->get('familiares');
//        $alergias = $request->get('alergias');
        
        
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        
        $signos = $paciente = $em->getRepository('DGPlusbelleBundle:Signos')->findBy(array('consulta'=>$idConsulta));
        
        $consulta = $paciente = $em->getRepository('DGPlusbelleBundle:Consulta')->find($idConsulta);
        
        if(count($paciente)!=0){
            //$persona = $em->getRepository('DGPlusbelleBundle:Persona')->find($paciente->getPersona()->getId());
            if(count($signos)==0){
                $signos = new Signos();
                $signos->setPeso($peso);
                $signos->setTalla($talla);
                $signos->setPresionSistolica($presionSistolica);
                $signos->setPresionDiastolica($presionDiastolica);
                $signos->setFrecRespiratotira($frecRespiratoria);
                $signos->setFrecCardiaca($frecCardiaca);
                $signos->setTemperatura($temperatura);
                $signos->setConsulta($consulta);
                $em->persist($signos);
                $em->flush();
            }
            else{
                $signos[0]->setPeso($peso);
                $signos[0]->setTalla($talla);
                $signos[0]->setPresionSistolica($presionSistolica);
                $signos[0]->setPresionDiastolica($presionDiastolica);
                $signos[0]->setFrecRespiratotira($frecRespiratoria);
                $signos[0]->setFrecCardiaca($frecCardiaca);
                $signos[0]->setTemperatura($temperatura);
                $signos[0]->setConsulta($consulta);
                $em->merge($signos[0]);
                $em->flush();
            }
            
//            $consulta->setHoraInicio(new \DateTime('now'));
//            $consulta->setHoraFin(new \DateTime('now'));
//            $consulta->setFechaConsulta(new \DateTime('now'));
//            $consulta->setPaciente($paciente);
//            $consulta->setReportePlantilla(1);
//            $consulta->setCostoConsulta(0);
            
            $em->merge($consulta);
            $em->flush();
            
            
            return new Response(json_encode($consulta->getId()));
        }
        else{
            return new Response(json_encode(0));
        }
        
        
        
    }
}
