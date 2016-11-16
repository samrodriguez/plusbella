<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Cita;
use DGPlusbelleBundle\Entity\Expediente;
use DGPlusbelleBundle\Form\CitaType;

/**
 * Cita controller.
 *
 * @Route("/admin/cita")
 */
class CitaController extends Controller
{

    /**
     * Lists all Cita entities.
     *
     * @Route("/", name="admin_cita")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        //$entities = $em->getRepository('DGPlusbelleBundle:Cita')->findAll();
        $sucursales= $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array('estado'=>true), array('id' => 'DESC'));
        $categorias = $em->getRepository('DGPlusbelleBundle:Categoria')->findAll();
        date_default_timezone_set('America/El_Salvador');
        
        $hoy = date('Y-m-d');
        $horaActual = date('H:i:s', time()-7200);
        $date = new \DateTime('now',new \DateTimeZone('America/El_Salvador'));
        $dateString = $date->format('-m-d');
        //var_dump($categorias);
        /*$dql = "SELECT exp.paciente"
                . "FROM DGPlusbelleBundle:Cita c, DGPlusbelleBundle:Paciente p, DGPlusbelleBundle:Expediente exp"
                . "WHERE c.id.paciente = p.id AND p.expediente AND exp.paciente = exp.id";
               
        $entities = $em->createQuery($dql)
                     ->getResult();
        
        var_dump($entities);
        */
        //var_dump($hoy);
        
        //$date = new DateTime();
        
        $sql = "SELECT pac.fechaNacimiento, pac.id, per.nombres, per.apellidos, per.telefono,per.telefono2 FROM DGPlusbelleBundle:Paciente pac "
                . "JOIN pac.persona per "
                . "WHERE pac.fechaNacimiento LIKE '%". $dateString ."%'";
        
        
        $cumpleaneros = $em->createQuery($sql)
                //->setParameters(array('empleado'=>$idEmpleado,'horaCita'=>$horaCita,'fechaCita'=>$fechaCita,'estado'=>'P'))
                ->getArrayResult();
        
        $sql = "SELECT c.id FROM DGPlusbelleBundle:Cita c "
                . "WHERE c.fechaCita LIKE '%". $hoy."%'";
        
        
        $citas = $em->createQuery($sql)
                //->setParameters(array('empleado'=>$idEmpleado,'horaCita'=>$horaCita,'fechaCita'=>$fechaCita,'estado'=>'P'))
                ->getArrayResult();
        
        
        //var_dump($cumpleaneros);
        
        //$cumpleaneros = $em->getRepository('DGPlusbelleBundle:Paciente')->findBy(array('fechaNacimiento' => $date), array('fechaNacimiento' => 'DESC'));
        
        
        $sucursal = $request->get('id');
        
        
             
        
        if($sucursal==null){
            $sucursal=3;
        }
        
        //var_dump($sucursal);
        $empleados = $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('estado'=>true));
        
        //$tratamiento = $em->getRepository('DGPlusbelleBundle:Tratamiento')->
        $trat = $em->getRepository('DGPlusbelleBundle:Tratamiento');
        
        //var_dump(get_class_methods($trat->obtenerConsultaTratActivo()));
        //var_dump(get_class_methods($trat->obtenerConsultaTratActivo()->getQuery()));
        //var_dump($trat->obtenerConsultaTratActivo()->getQuery()->getResult());
        $tratamientosConsulta = $trat->obtenerConsultaTratActivo()->getQuery()->getResult();
                
        
        /*$dql = "SELECT c from DGPlusbelleBundle:Cita c "
                . "WHERE c.fechaCita <:hoy AND c.horaCita <:horaActual AND c.estado='P'";*/
//        $dql = "SELECT c from DGPlusbelleBundle:Cita c "
//                . "WHERE c.fechaCita <:hoy  OR (c.fechaCita =:hoy AND c.horaCita <:horaActual) AND c.estado='P'";
//        $citaspasadas = $em->createQuery($dql)
//                    ->setParameters(array('hoy'=>$hoy,'horaActual'=>$horaActual))
//                     ->getResult();
        //var_dump($hoy);
        //var_dump($horaActual);
        //echo count($citaspasadas);
        //$citaspasadas = $em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('estado'=>'P'));
        //var_dump($citaspasadas);
        
//        foreach($citaspasadas as $row){
//            $row->setEstado('N');
//            $em->persist($row);
//            //$em->flush();
//        }
//        $em->flush();
        
       
        return array(
            //'entities' => $entities,
            'consultas' => $tratamientosConsulta,
            'sucursales' => $sucursales,
            'categorias' => $categorias,
            'empleados'=>$empleados,
            'cumpleaneros'=>$cumpleaneros,
            'citas'=>$citas,
            'sucursal'=>$sucursal,
            'hoy'=>$date,
        );
    }
    /**
     * Creates a new Cita entity.
     *
     * @Route("/", name="admin_cita_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Cita:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Cita();
        $entity->setFechaRegistro(new \DateTime('now'));
        $entity->setEstado('P');
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        $data = $request->request->all();
        $idPac = $data['dgplusbellebundle_cita']['paciente'];
        //var_dump($idPac);
        $pacienteObj = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPac);
        
        
        $idEmpleado = $entity->getEmpleado()->getId();
        $horaCita = $entity->getHoraCita()->format('H:i:s');
        $horaFin = $entity->getHoraFin()->format('H:i:s');
        $fechaCita = $entity->getFechaCita()->format('Y-m-d');
        
        ////Nuevos valores
        
        
        $paquete = $_POST['cita_paquete'];
        //$tipo_cita= $_POST['dgplusbellebundle_cita[tipoCita]'];
        $trat1= $_POST['cita_trat1'];
        $trat2= $_POST['cita_trat2'];
        $tipoCita= $data['dgplusbellebundle_cita']['tipoCita'];
        //var_dump($entity);
//        var_dump($tipoCita);
//        die();
//        var_dump($paquete);
//        var_dump($trat1);
//        var_dump($trat2);
        
        $entity->setTipoCita(intval($tipoCita));
        
        switch($tipoCita){
            case 0://///Consultas
                
                $entity->setTratamiento1(null);
                $entity->setTratamiento2(null);
                
                break;
            case 1://///Tratamientos sin paquetes
                
                $entity->setTratamiento1($trat1);
                $entity->setTratamiento2($trat2);
                
                break;
            case 2://///Tratamientos de paquetes
                
                $entity->setTratamiento1($trat1);
                $entity->setTratamiento2($trat2);
                
                break;
        }
        
        
        
        
        
        
        
        
//        $idEmpleado = $entity->getEmpleado();
//        $horaCita = $entity->getHoraCita();
//        $horaFin = $entity->getHoraFin();
//        $fechaCita = $entity->getFechaCita();
        
        //var_dump($entity->getEmpleado()->getId());
        //var_dump($entity->getHoraCita());
        //var_dump($idEmpleado);
        //var_dump($horaCita);
        //var_dump($fechaCita);
        //echo gmdate("H:i", $horaCita);
        //$horaCita = date_format($horaCita,'H:i');
        
        //$horaCita = strtotime($horaCita);
        //var_dump($horaCita);
        
//        $cita = $em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('empleado'=>$idEmpleado,'horaCita'=>$horaCita,'fechaCita'=>$fechaCita));
        //$cita = $em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('empleado'=>$idEmpleado,'horaCita'=>$horaCita,'fechaCita'=>$fechaCita,'estado'=>'P'));
//        var_dump($idEmpleado);
//        var_dump($horaCita);
//        var_dump($horaFin);
//        var_dump($fechaCita);
        $entity->setPaciente($pacienteObj);
        $dql = "SELECT c from DGPlusbelleBundle:Cita c "
                . "WHERE c.empleado=:empleado "
                . "AND c.horaCita<=:horaCita "
                . "AND c.horaFin>:horaCita "
                . "AND c.fechaCita=:fechaCita "
                . "AND c.estado=:estado";
        $cita = $em->createQuery($dql)
                ->setParameters(array('empleado'=>$idEmpleado,'horaCita'=>$horaCita,'fechaCita'=>$fechaCita,'estado'=>'P'))
                ->getArrayResult();
        
        
        $dql = "SELECT ca from DGPlusbelleBundle:CierreAdministrativo ca "
                . "WHERE ca.empleado=:empleado "
                . "AND ca.horaInicio<=:horaCita "
                . "AND ca.horaFin>:horaCita "
                . "AND ca.fecha=:fechaCita ";
        $cierres = $em->createQuery($dql)
                ->setParameters(array('empleado'=>$idEmpleado,'horaCita'=>$horaCita,'fechaCita'=>$fechaCita))
                ->getArrayResult();
        //var_dump($cierres);
        //die();
        
        if($horaFin<$horaCita){
            //var_dump('se');
            return array(
                'paciente',$pacienteObj,
                'entity' => $entity,
                'form'   => $form->createView(),
                'mensaje'=> 'La hora de fin no puede ser menor a la de inicio de cita',
            );
        }
        
        
        
        if(count($cita)==0){
            if(count($cierres)==0){
//                if ($form->isValid()) {
                    $paciente = $entity->getPaciente();
    //                var_dump($paciente);
                    if($paciente!=null){


                        $expediente = $paciente->getExpediente();
                        if(count($expediente)==0){
                            //var_dump($expediente);
        //                    echo "no tiene expediente";
                            $expNumero = $this->generarExpediente($paciente);
                        }
                        else{
                            $expNumero= $expediente[0]->getNumero();
        //                    echo "tiene expediente";
                        }
                    }
    //                echo $expNumero;
    //                die();
                    $usuario= $this->get('security.token_storage')->getToken()->getUser();
                    $em = $this->getDoctrine()->getManager();
                    
                    
                    
                    
                    
                    
                    
                    
                    $em->persist($entity);
                    $em->flush();
                    $this->get('bitacora')->escribirbitacora("Se creo una nueva cita",$usuario->getId());
                    //var_dump('s');
                    return $this->redirect($this->generateUrl('admin_cita'));
//                }
            }
            else{
                //var_dump('ss');
                return array(
                    'paciente',$pacienteObj,
                    'entity' => $entity,
                    'form'   => $form->createView(),
                    'mensaje'=> 'El empleado seleccionado tiene reserva de tiempo el día y hora seleccionada',
                );
            }
        }
        else{
            
            return array(
               // 'sucursal'=>$sucursal,
                'entity' => $entity,
                'paciente',$pacienteObj,
                'form'   => $form->createView(),
                'mensaje'=> 'Ya hay una cita programada en esa fecha y hora, para el técnico que selecciono',
            );
        }
        
        
            

        
    }

    /**
     * Creates a form to create a Cita entity.
     *
     * @param Cita $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Cita $entity)
    {
        $form = $this->createForm(new CitaType(), $entity, array(
            'action' => $this->generateUrl('admin_cita_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-primary btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Cita entity.
     *
     * @Route("/new", name="admin_cita_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new Cita();
        
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del paciente
        $request = $this->getRequest();
        
        $cadena= $request->get('id');
        
        $id = substr($cadena, 1);
        
        
        //$sucursal = 1;
        
        $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->find(1);
        $entity->setSucursal($sucursal);
        
        
        //Busqueda del paciente
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        //Seteo del paciente en la entidad
        $entity->setPaciente($paciente);
        
        //Enlace del form con la entidad
        $form   = $this->createCreateForm($entity);
        //var_dump($form->getConfig()->getData());
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'mensaje'=> ' ',
            'paciente'=>$paciente,
        );
    }

    /**
     * Finds and displays a Cita entity.
     *
     * @Route("/{id}/show", name="admin_cita_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Cita')->find($id);

        if (!$entity) {
            //throw $this->createNotFoundException('Unable to find Cita entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Cita entity.
     *
     * @Route("/{id}/edit", name="admin_cita_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Cita')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cita entity.');
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
    * Creates a form to edit a Cita entity.
    *
    * @param Cita $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Cita $entity)
    {
        $form = $this->createForm(new CitaType(), $entity, array(
            'action' => $this->generateUrl('admin_cita_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Cita entity.
     *
     * @Route("/{id}/update", name="admin_cita_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Cita:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario= $this->get('security.token_storage')->getToken()->getUser();
        $entity = $em->getRepository('DGPlusbelleBundle:Cita')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cita entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('bitacora')->escribirbitacora("Se actualizo una cita",$usuario->getId());
            return $this->redirect($this->generateUrl('admin_cita_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Cita entity.
     *
     * @Route("/{id}/delete", name="admin_cita_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        $usuario= $this->get('security.token_storage')->getToken()->getUser();
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Cita')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Cita entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('bitacora')->escribirbitacora("Se elimino una cita",$usuario->getId());
        }

        return $this->redirect($this->generateUrl('admin_cita'));
    }

    /**
     * Creates a form to delete a Cita entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_cita_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    
    
    
     /**
     * @Route("/dias/get/{idEmp}", name="get_dias", options={"expose"=true})
     * @Method("GET")
     */
    public function getDiasAction(Request $request, $idEmp) {
        
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $dql = "SELECT ho.diaHorario 
                    FROM DGPlusbelleBundle:Horario ho
                    JOIN ho.empleado emp
                WHERE emp.id =:idEmp";
        $dias['regs'] = $em->createQuery($dql)
                ->setParameter('idEmp', $idEmp)
                ->getArrayResult();
        //var_dump($regiones);
        return new Response(json_encode($dias));
    }
    
    
    /**
     * @Route("/horas/get/{idEmp}/{dia}", name="get_horas", options={"expose"=true})
     * @Method("GET")
     */
    public function getHorasAction(Request $request, $idEmp,$dia) {
        
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $dql = "SELECT ho.horaInicio, ho.horarioFin
                    FROM DGPlusbelleBundle:Horario ho
                    JOIN ho.empleado emp
                WHERE emp.id =:idEmp AND ho.diaHorario =:dia";
        $horas['regs'] = $em->createQuery($dql)
                ->setParameters(array('idEmp'=>$idEmp,'dia'=>$dia))
                ->getArrayResult();
        //var_dump($horas);
        $inicio=$horas['regs'][0]['horaInicio']->format('H:i');
        $fin=$horas['regs'][0]['horarioFin']->format('H:i');
        $horasExtraidas['regs']=array();
        //var_dump($inicio);
        
        ///array_push($horasExtraidas['regs'], $inicio, $fin); 
        
        

        $time = strtotime($inicio);
        $time = date("H:i", strtotime('+30 minutes', $time));
        $timeString = $inicio;
        
        //var_dump($timeString);

        $w = strtotime($inicio);
        $s = strtotime($fin);

        

        $st_time    =   strtotime($inicio);
        $end_time   =   strtotime($fin);
        $cur_time   =   strtotime("now");
        

        $timeInicio = strtotime($inicio);
        $timeIncrementos=$timeInicio;
        $timeFin = strtotime($fin);
        $stringInicio = "";
        $stringIncrementos="";
        $stringFin="";
        
        //$endTime = date("H:i", strtotime('+30 minutes', $time));
        //echo $inicio."\n";
        while($timeIncrementos<$timeFin){
            //echo $timeIncrementos."\n";
            //$time = strtotime($inicio);
            $stringIncrementos = date("H:i", strtotime('+30 minutes', $timeIncrementos));
            //echo $timeIncrementos;
            $timeIncrementos = strtotime($stringIncrementos);
            //echo $timeIncrementos;
            //echo date("H:i", $timeIncrementos)."\n";
            array_push($horasExtraidas['regs'], $stringIncrementos);
        }
        /*while($w<$s){
            /*$time = strtotime($timeString);
            $timek = date("H:i", strtotime('+30 minutes', $time));
            $timeString = date('H:i',$timek);*/
           /* $time = strtotime($inicio);
            $timeString = date("H:i", strtotime('+30 minutes', $time));
            array_push($horasExtraidas['regs'], $timeString);
        }*/
        


        //$horas2['regs'] = 
        //var_dump($horas['regs'][0]['horarioFin']);
        //var_dump($horasExtraidas);
        return new Response(json_encode($horas));
    }
    
    
    
     /**
     * @Route("/nuevahora/get/{id}/{delta}/{fecha}", name="get_nuevaHora", options={"expose"=true})
     * @Method("GET")
     */
    public function nuevaHoraAction(Request $request, $id, $delta, $fecha) {
        
        $request = $this->getRequest();
        date_default_timezone_set('America/El_Salvador');
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Cita')->find($id);
        $empleado=$entity->getEmpleado();
        //var_dump($empleado->getId());
        //$entityDuplicada = $em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('empleado'=>$empleado->getId(),'horaInicio'=>$entity->getHoraInicio()));
        $horaInicial = $entity->getHoraCita();
        $horaFinal= $entity->getHoraFin();
        
        
        $time = strtotime($fecha);
        //var_dump($entity->getFechaCita());
        $newformat = date('Y-m-d',$time);
        //var_dump($newformat);
        //var_dump( count( $entityDuplicada));
        $exito['regs']=0;
        if(isset($entity)){
            
                
            
                //var_dump($entity);
                $hora = $entity->getHoraCita()->format("H:i:s");

                $horaTime = strtotime($hora);
                $horaNueva = date("H:i:s", strtotime($delta.' minutes', $horaTime));
                //var_dump($horaNueva);
                $entity->setHoraCita(new \DateTime($horaNueva));
                
                $dql = "SELECT c
                    FROM DGPlusbelleBundle:CierreAdministrativo c
                    WHERE c.empleado =:idEmp AND (c.horaInicio<= :horaNueva AND c.horaFin>:horaNueva) AND c.fecha=:fecha";
                $cierreAdmin = $em->createQuery($dql)
                                    ->setParameters(array('idEmp'=>$empleado->getId(),'horaNueva'=>$horaNueva,'fecha'=>$newformat))
                                    ->getArrayResult();
                // cambio de la hora final
                //var_dump($cierreAdmin);
                $horaFin = $entity->getHoraFin()->format("H:i:s");

                $horaFinTime = strtotime($horaFin);
                $horaNuevaFin = date("H:i:s", strtotime($delta.' minutes', $horaFinTime));
                
                $entity->setHoraFin(new \DateTime($horaNuevaFin));
                
                //echo "hora nueva: ";
                //var_dump($entity->getHoraInicio());
                $dql = "SELECT c
                    FROM DGPlusbelleBundle:Cita c
                    WHERE c.empleado =:idEmp AND c.horaCita =:hora AND c.fechaCita=:fecha AND c.id <>:id AND c.estado='P'";
                $entityDuplicada = $em->createQuery($dql)
                                    ->setParameters(array('idEmp'=>$empleado->getId(),'hora'=>$entity->getHoraCita()->format('H:i:s'),'fecha'=>$newformat,'id'=>$entity->getId()))
                                    ->getArrayResult();
                if(count($entityDuplicada)==0){
                    //$hoy = new \DateTime('now');
                    //var_dump($hoy);
                    $today_dt = new \DateTime('now');
                    $expire_dt = new \DateTime($newformat);
                    $fechaReprogramada = date("Y-m-d",$time);
                    
                    //var_dump($today_dt->format("Y-m-d"));
                    //var_dump(date('Y-m-d',$newformat));
                    $today_dt = $today_dt->format("Y-m-d");
                    $expire_dt = $newformat;
                    //var_dump($fechaReprogramada);
                    //var_dump($today_dt);
                    
                    if ($fechaReprogramada < $today_dt) {
                        //var_dump($newformat);
                        //var_dump($expire_dt);
                        $exito['regs']=3;//Error, intenta reprogramar la cita a un dia anterior a "hoy"
                    }
                    else{
                        $var = date('H:i:s');
                        if ($fechaReprogramada == $today_dt) {
                            //echo "propbando3";
                            //var_dump($var);
                            //var_dump($horaNueva);
                            if($horaNueva>$var ){
                            //echo "probando";
                                if($entity->getEstado()=="P"){
                                    if(count($cierreAdmin)==0){
                                        $entity->setFechaCita(new \DateTime($newformat));
                                        $em->persist($entity);
                                        $em->flush();   
                                        $exito['regs']=0; //Cita reprogramada con exito
                                    }
                                    else{
                                        $exito['regs']=5; //Existe un cierre administrativo para el empleado
                                    }
                                }
                                else{
                                    $exito['regs']=1;//Error, La cita tiene estado asistida o cancelada
                                }
                            }
                            else{
                                $exito['regs']=3;
                            }
                        }
                        else{
                            //echo "propbando2";
                            if($entity->getEstado()=="P"){
                                if(count($cierreAdmin)==0){
                                    $entity->setFechaCita(new \DateTime($newformat));
                                    $em->persist($entity);
                                    $em->flush();   
                                    $exito['regs']=0; //Cita reprogramada con exito
                                }
                                else{
                                    $exito['regs']=5; //Existe un cierre administrativo para el empleado
                                }
                            }
                            else{
                                $exito['regs']=1;//Error, La cita tiene estado asistida o cancelada
                            }
                        }
	                 
                        
                    }
                }
                else{
                    $exito['regs']=2;//Error, La cita coincide con otro registro
                }
        }
        else{
            $exito['regs']=3;
        }
//var_dump($exito['regs']);
        return new Response(json_encode($exito));
    }
    
    
    
    
    /**
     * @Route("/infocita/get/{id}", name="get_infoCita", options={"expose"=true})
     * @Method("GET")
     */
    public function getInfoCitaAction(Request $request, $id) {
        
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $dql = "SELECT c.id,exp.numero, pac.nombres, pac.apellidos, t.nombre as nombreTratamiento,
                per.nombres as primerNombreEmp, per.apellidos as primerApellidoEmp, 
                c.fechaCita, c.horaCita,c.horaFin, c.estado, c.descripcion,c.tipoCita as citaPor, c.tratamiento1, c.tratamiento2, c.paquete
                    FROM DGPlusbelleBundle:Cita c
                    JOIN c.empleado emp
                    LEFT JOIN c.tratamiento t
                    JOIN c.paciente p
                    JOIN p.persona pac
                    JOIN emp.persona per
                    JOIN p.expediente exp                    
                WHERE c.id =:id";
        $cita['regs'] = $em->createQuery($dql)
                ->setParameter('id', $id)
                ->getArrayResult();
        
        //var_dump($cita);
        if(count($cita['regs'])!=0){
            $cita['regs'][0]["numero"] = strtoupper($cita['regs'][0]["numero"]);
            $cita['regs'][0]["nombres"] = ucwords($cita['regs'][0]["nombres"]);
            $cita['regs'][0]["apellidos"] = ucwords($cita['regs'][0]["apellidos"]);
        }
        else{
            $dql = "SELECT c.id, t.nombre as nombreTratamiento,
                per.nombres as primerNombreEmp, per.apellidos as primerApellidoEmp, 
                c.fechaCita, c.horaCita,c.horaFin, c.estado
                    FROM DGPlusbelleBundle:Cita c
                    JOIN c.empleado emp
                    LEFT JOIN c.tratamiento t
                    JOIN emp.persona per
                WHERE c.id =:id";
            $cita['regs'] = $em->createQuery($dql)
                    ->setParameter('id', $id)
                    ->getArrayResult();
            
            $cita['regs'][0]["numero"] = "-";
            $cita['regs'][0]["nombres"] = "Paciente no registrado";
            $cita['regs'][0]["apellidos"] = "";
            
//            $cita['regs'][0]["nombreTratamiento"] = ucwords($cita['regs'][0]["nombreTratamiento"]);
//            $cita['regs'][0]["primerNombreEmp"] = ucwords($cita['regs'][0]["primerNombreEmp"]);
//            $cita['regs'][0]["primerApellidoEmp"] = ucwords($cita['regs'][0]["primerApellidoEmp"]);
//            $cita['regs'][0]["fechaCita"] = $cita['regs'][0]["fechaCita"]->format("d-m-Y");
//            $cita['regs'][0]["horaCita"] = $cita['regs'][0]["horaCita"]->format("H:i");
            //$cita['regs'] = 0;
        }
        
        
        $cita['regs'][0]["nombreTratamiento"] = ucwords($cita['regs'][0]["nombreTratamiento"]);
        $cita['regs'][0]["primerNombreEmp"] = ucwords($cita['regs'][0]["primerNombreEmp"]);
        $cita['regs'][0]["primerApellidoEmp"] = ucwords($cita['regs'][0]["primerApellidoEmp"]);
        $cita['regs'][0]["fechaCita"] = $cita['regs'][0]["fechaCita"]->format("d-m-Y");
        $cita['regs'][0]["horaCita"] = $cita['regs'][0]["horaCita"]->format("H:i");
        $cita['regs'][0]["horaFin"] = $cita['regs'][0]["horaFin"]->format("H:i");
        
        
        switch ($cita['regs'][0]["citaPor"]){
            case 0:
                
                break;
            case 1:
                if($cita['regs'][0]["tratamiento1"]!=null){
                    $tratamiento1= $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($cita['regs'][0]["tratamiento1"]);
                    if(count($tratamiento1)!=0)
                        $cita['regs'][0]["tratamiento1"] = $tratamiento1->getTratamiento()->getNombre();
                    else
                        $cita['regs'][0]["tratamiento1"] = '';
                }
                else{
                    $cita['regs'][0]["tratamiento1"] = '';
                }
                if($cita['regs'][0]["tratamiento2"]!=null){
                    $tratamiento2= $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($cita['regs'][0]["tratamiento2"]);
                    if(count($tratamiento2)!=0)
                        $cita['regs'][0]["tratamiento2"] = $tratamiento2->getTratamiento()->getNombre();
                    else
                        $cita['regs'][0]["tratamiento2"] = '';
                }
                else{
                    $cita['regs'][0]["tratamiento2"] = '';
                }
                
                break;
            case 2:
                if($cita['regs'][0]["paquete"]!=null){
                    $paquete = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($cita['regs'][0]["paquete"]);
                    if(count($paquete)!=0)
                        $cita['regs'][0]["paquete"] = $tratamiento1->getPaquete()->getNombre();
                    else
                        $cita['regs'][0]["paquete"] = '';
                }
                else{
                    $cita['regs'][0]["paquete"] = '';
                }
                if($cita['regs'][0]["tratamiento1"]!=null){
                    $tratamiento1= $em->getRepository('DGPlusbelleBundle:Tratamiento')->find($cita['regs'][0]["tratamiento1"]);
                    if(count($tratamiento1)!=0)
                        $cita['regs'][0]["tratamiento1"] = $tratamiento1->getTratamiento()->getNombre();
                    else
                        $cita['regs'][0]["tratamiento1"] = '';
                }
                else{
                    $cita['regs'][0]["tratamiento1"] = '';
                }
                if($cita['regs'][0]["tratamiento1"]!=null){
                    $tratamiento2= $em->getRepository('DGPlusbelleBundle:Tratamiento')->find($cita['regs'][0]["tratamiento2"]);
                    if(count($tratamiento2)!=0)
                        $cita['regs'][0]["tratamiento2"] = $tratamiento2->getTratamiento()->getNombre();
                    else
                        $cita['regs'][0]["tratamiento2"] = '';
                }
                else{
                    $cita['regs'][0]["tratamiento2"] = '';
                }
                break;
        }
        
        switch ($cita['regs'][0]["estado"]){
            case "A":
                $cita['regs'][0]["estado"] = "Asistida";
                break;
            case "P":
                $cita['regs'][0]["estado"] = "Pendiente";
                break;
            case "C":
                $cita['regs'][0]["estado"] = "Cancelada";
                break;
            case "N":
                $cita['regs'][0]["estado"] = "No asistio";
                break;
        }
        
        //var_dump($cita['regs'][0]["primerNombre"]);
        //var_dump($cita);
        
        return new Response(json_encode($cita));
    }
    
    
    
    /**
     * @Route("/verificarcoincidenciacita/get/{idempleado}/{fecha}/{hora}", name="get_existeCita", options={"expose"=true})
     * @Method("GET")
     */
    public function exitsteCitaAction(Request $request, $idempleado, $fecha, $hora) {
        
        $request = $this->getRequest();
//        var_dump($idempleado);
//        var_dump($fecha);
        $fechaobj = new \DateTime($fecha);
        
//        var_dump($fechaobj->format('Y-m-d'));
        $em = $this->getDoctrine()->getEntityManager();
        
        $dql = "SELECT c.id FROM DGPlusbelleBundle:Cita c WHERE c.empleado =:empleado AND c.fechaCita=:fecha AND c.horaCita=:hora AND c.estado='P'";
        $cita['regs'] = $em->createQuery($dql)
                ->setParameters(array('empleado'=>$idempleado,'fecha'=>$fechaobj->format('Y-m-d'),'hora'=>$hora))
                ->getArrayResult();
        
        //var_dump($cita);
        
        
        
        //var_dump($cita['regs'][0]["primerNombre"]);
//        var_dump($cita);
        
        return new Response(json_encode($cita));
    }
    
    /**
     * @Route("/cancelarcita/get/{id}", name="get_cancelarCita", options={"expose"=true})
     * @Method("GET")
     */
    public function cancelarCitaAction(Request $request, $id) {
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $entity = $em->getRepository('DGPlusbelleBundle:Cita')->find($id);
        
        //var_dump($entity);
        if(count($entity)!=0){
            
            $entity->setEstado("C");
            
            $cita['regs'] = 0;  //Cita encontrada y modificada con éxito
        }
        else{
            $cita['regs'] = 1;  //Cita no encontrada
        }
        $em->merge($entity);
        $em->flush();
        //var_dump($cita);
        
        //var_dump($cita['regs'][0]["primerNombre"]);
        //var_dump($cita);
        
        return new Response(json_encode($cita));
    }
    
    
    /**
     * @Route("/asistidacita/get/{id}", name="get_asistidaCita", options={"expose"=true})
     * @Method("GET")
     */
    public function asistidaCitaAction(Request $request, $id) {
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $entity = $em->getRepository('DGPlusbelleBundle:Cita')->find($id);
        
        
        if(count($entity)!=0){
            
            $entity->setEstado("A");
            
            $cita['regs'] = 0;  //Cita encontrada y modificada con éxito
        }
        else{
            $cita['regs'] = 1;  //Cita no encontrada
        }
        $em->merge($entity);
        $em->flush();
//        var_dump($entity);
//        die();
        //var_dump($cita);
        
        //var_dump($cita['regs'][0]["primerNombre"]);
        //var_dump($cita);
        
        return new Response(json_encode($cita));
    }
    
    
    
    /**
     * Metodo que sirve para generar el expediente del paciente
     *
     * @param \DGPlusbelleBundle\Entity\Paciente $paciente
     *
     */
    private function generarExpediente($paciente)
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
        $flag=true;
        $i=0;
//        echo $nombre;
        while ($flag){
            if($nombre[$i]==' '){
                $i++;
//                echo '"'.$nombre.'"';
            }
            else{
                $flag=false;
                $nombre=$nombre[$i];
            }
        }
//        echo $nombre;
        $flag=true;
        $i=0;
        while ($flag){
            if($apellido[$i]==' '){
                $i++;
            }
            else{
                $flag=false;
                $apellido=$apellido[$i];
            }
        }
        
        $numeroExp = substr(strtoupper(trim($nombre)), 0,1).substr(strtoupper(trim($apellido)), 0,1).date("Y");
//        $numeroExp = strtoupper ($numeroExp);
//        echo $numeroExp;
                
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
//        var_dump($numeroExp);
//        die();
        //Seteo de valores del expediente
        $expediente->setFechaCreacion(new \DateTime('now'));
        $expediente->setHoraCreacion(new \DateTime('now'));
        $expediente->setEstado(true);
        $expediente->setNumero($numeroExp);
        $expediente->setPaciente($paciente);
        $expediente->setUsuario($user);

//        $paciente->setExpediente($expediente);
        $em->persist($expediente);
        $em->flush();
        return $numeroExp;
    }
    
    
    
    
    
    
    
    
    /**
     * Displays a form to create a new Cita entity.
     *
     * @Route("/new/cita", name="admin_cita_new2", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function new2Action(Request $request)
    {
        $entity = new Cita();
        
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del paciente
        $request = $this->getRequest();
        
        $fecha = $request->get('fecha');
        $hora = $request->get('hora');
        //var_dump($fecha);
        //var_dump($hora);
        
        //$id = substr($cadena, 1);
        
        $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->find(1);
        $entity->setSucursal($sucursal);
        
        //Busqueda del paciente
        $pacientes = $em->getRepository('DGPlusbelleBundle:Paciente')->findAll();
        //Seteo del paciente en la entidad
        //$entity->setPaciente($paciente);
        $fecha = new \DateTime($fecha);
        $hora = new \DateTime($hora);
        //var_dump($fecha);
        $entity->setFechaCita($fecha);
        $entity->setHoraCita($hora);
        
        //Enlace del form con la entidad
        $form   = $this->createCreateForm($entity);
        //var_dump($form->getConfig()->getData());
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'mensaje'=> ' ',
        );
    }
    
    
    /**
     * Displays a form to create a new Cita entity.
     *
     * @Route("/recordatorio/cita", name="admin_cita_reminder", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function reminderAction(Request $request)
    {
//        $entity = new Cita();
//        
        $em = $this->getDoctrine()->getManager();
//        
//        //Recuperación del paciente
//        $request = $this->getRequest();
//        
        $id = $request->get('id');
        
//        var_dump($id);
        //die();  
        //var_dump($hora);
        
        //$id = substr($cadena, 1);
        
        $citaObj = $em->getRepository('DGPlusbelleBundle:Cita')->find($id);
        $paciente = $citaObj->getPaciente();
        $persona = $paciente->getPersona();
        $email = $persona->getEmail();
        $EmailFrom = "escalon@laplusbelle.com.sv";
        //$EmailTo = "mario@digitalitygarage.com";

        $Subject = "Recordatorio cita";
        
        

        // validation
        $validationOK=true;
        if (!$validationOK) {
          print "<meta http-equiv=\"refresh\" content=\"0;URL=error.htm\">";
          exit;
        }

        // prepare email body text
//        $Body = "prueba";
        $Body = "Saludos, ".$persona->getNombres()." ".$persona->getApellidos().". Este correo es un recordatorio de tu cita el día ".$citaObj->getFechaCita()->format('d-m-Y')." a las ".$citaObj->getHoraCita()->format('H:i')."";
        
        
//        $Body = "<table style=\"width: 540px; margin: 0 auto;\">
//                                            <tr>
//                                                <td>
//                                                    <div class=\"pull-left\">
//                                                      <img style=\"width:80%;\" src=\"http://www.laplusbelle.com.sv/recursos/img/logo.jpg\">
//                                                    </div>
//                                                </td>
//                                                <td>  
//                                                    <div class=\"pull-right\">
//                                                      <img style=\"width:80%;float:right;\" src=\"http://www.laplusbelle.com.sv/recursos/img/logo.jpg\">
//                                                    </div>
//                                                </td>
//                                            </tr>
//                                            <tr>
//                                                <td colspan=\"2\">
//                                                    <p>Saludos, ".$persona->getNombres()." ".$persona->getApellidos()."</p>
//                                                    <p>Este correo es un recordatorio de tu cita el día <strong>".$citaObj->getFechaCita()->format('d-m-Y')."</strong> a las <strong>".$citaObj->getHoraCita()->format('H:i')."</strong>. Para mayor información de contacto visita el siguiente link. <a href=\"http://www.laplusbelle.com.sv/contactanos.php\">Más información</a> </p>
//                                                </td>
//                                            </tr>
//                                        </table>";

        // send email 
        

        // redirect to success page 
//        if ($success){
//          $flag=0;
//        }
//        else{
//          $flag=1;
//        }
//        var_dump($email);
//        die();
        // 0 para indicar correo no enviado
        if($email !=''){
            //enviar correo
            $success = mail($email, $Subject, $Body, "From: <$EmailFrom>");
            $flag=0; //correo enviado
        }
        else{
            //No tiene correo en el sistema
            $flag=1;
        }
        
        
        
        return new Response(json_encode($flag));
    }

    /**
     * @Route("/cumpleanos/get/listado", name="admin_cumpleanos", options={"expose"=true})
     * @Method("GET")
     */
    public function listadoCumpleanosAction(Request $request){
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                $fecha = $request->query->get('fecha');
                $em = $this->getDoctrine()->getEntityManager();
                //var_dump($fecha);
                $date=  date_create_from_format('d-m-Y', $fecha);
                $dateString = $date->format('Y-m-d');
                //var_dump($dateString);
                $sql = "SELECT obj.id as id FROM DGPlusbelleBundle:Paciente obj "
                            . " WHERE Month(obj.fechaNacimiento) = Month('".$dateString."')"
                            . " AND Day(obj.fechaNacimiento) = Day('".$dateString."')";
                $rowsTotal = $em->createQuery($sql)
                            //->setParameter('fecha', "'%".$dateString)
                            ->getResult();
                    
                $row['draw']=$draw++;  
                $row['recordsTotal'] = count($rowsTotal);
                $row['recordsFiltered']= count($rowsTotal);
                $row['data']= array();

                $arrayFiltro = explode(' ',$busqueda['value']);
                
                $orderParam = $request->query->get('order');
                $orderBy = $orderParam[0]['column'];
                $orderDir = $orderParam[0]['dir'];
                $orderByText="";
                switch(intval($orderBy)){
                    case 0:
                        $orderByText = "nombres";
                        break;
                    case 1:
                        $orderByText = "telefono";
                        break;
                    case 2:
                        $orderByText = "";
                        break;
                }
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){
                    $sql = "SELECT pac.fecha_nacimiento as fechaNacimiento, pac.id, concat(per.nombres,' ',per.apellidos) as  nombresComp, per.telefono as telefono,per.telefono2 FROM paciente pac "
                    . "INNER JOIN persona per on (per.id=pac.persona)"
                    . " WHERE Month(pac.fecha_nacimiento) = Month('".$dateString."') "
                    . " AND Day(pac.fecha_nacimiento) = Day('".$dateString."') " 
                    . " HAVING upper(CONCAT(fechaNacimiento,' ',nombresComp,' ',telefono)) LIKE upper('%".$busqueda['value']."%') ";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $row['data'] = $stmt->fetchAll();
                    $row['recordsFiltered']= count($row['data']);
                    $sql = "SELECT pac.fecha_nacimiento as fechaNacimiento, pac.id, UPPER(concat(per.nombres,' ',per.apellidos)) as nombresComp, per.telefono as telefono,per.telefono2 FROM paciente pac "
                    . "INNER JOIN persona per on (per.id=pac.persona)"
                    . " WHERE Month(pac.fecha_nacimiento) = Month('".$dateString."') "
                    . " AND Day(pac.fecha_nacimiento) = Day('".$dateString."') "
                    . " HAVING upper(CONCAT(fechaNacimiento,' ',nombresComp,' ',telefono)) LIKE upper('%".$busqueda['value']."%') "
                    . "ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $row['data'] = $stmt->fetchAll();
                }
                else{
                    $sql = "SELECT DATE_FORMAT(pac.fecha_nacimiento,'%d-%m-%Y') as fechaNacimiento, pac.id, UPPER(concat(per.nombres,' ',per.apellidos)) as  nombresComp, per.telefono as telefono,per.telefono2 FROM paciente pac "
                    . "INNER JOIN persona per on (per.id=pac.persona)"
                    . " WHERE Month(pac.fecha_nacimiento) = Month('".$dateString."') "
                    . " AND Day(pac.fecha_nacimiento) = Day('".$dateString."') "
                    . " HAVING upper(CONCAT(fechaNacimiento,' ',nombresComp,' ',telefono)) LIKE upper('%".$busqueda['value']."%') "
                    . "ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $row['data'] = $stmt->fetchAll();
                }
                
                return new Response(json_encode($row));
            } catch (\Exception $e) {  
                var_dump($e);
                if(method_exists($e,'getErrorCode')){ 
                    switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $serverOffline= $this->getParameter('app.serverOffline');
                            $row['data'][0]['name'] =$serverOffline.'. CODE: '.$e->getErrorCode();
                        break;
                        default :
                            $row['data'][0]['name'] = $e->getMessage();                           
                        break;
                    }
                    $row['data'][0]['chk'] ='';
                    
                    $row['recordsFiltered']= 0;
                    }
                    else{
                            $data['error']=$e->getMessage();
                    }
                return new Response(json_encode($row));
        }
    }
    
    
    
    
    
    
    
    /**
     * @Route("/citas/get/listado", name="admin_citas_listado", options={"expose"=true})
     * @Method("GET")
     */
    public function listadoCitasAction(Request $request){
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                $fechaInicio = $request->query->get('fechaInicio');
                $fechaFin = $request->query->get('fechaFin');
                $sucursal = $request->query->get('sucursal');
                $em = $this->getDoctrine()->getEntityManager();
//                var_dump($fechaInicio);
//                var_dump($fechaFin);
                $date=  date_create_from_format('d-m-Y', $fechaInicio);
                $fechaInicio = $date->format('Y-m-d');
                $date=  date_create_from_format('d-m-Y', $fechaFin);
                $fechaFin= $date->format('Y-m-d');
                //var_dump($dateString);
                
                $sql = "SELECT c.id as id FROM DGPlusbelleBundle:Cita c "
                            . "JOIN c.sucursal suc"
                            . " WHERE c.fechaCita>= :fechaInicio "
                            . " AND c.fechaCita <= :fechaFin AND suc.id= :sucursal";
                $rowsTotal = $em->createQuery($sql)
                            ->setParameters(array('fechaInicio'=> $fechaInicio,'fechaFin'=> $fechaFin,'sucursal'=>$sucursal))
                            ->getResult();
                    
                    //var_dump($rowsTotal);
                $row['draw']=$draw++;  
                $row['recordsTotal'] = count($rowsTotal);
                $row['recordsFiltered']= count($rowsTotal);
                $row['data']= array();

                $arrayFiltro = explode(' ',$busqueda['value']);
                
                $orderParam = $request->query->get('order');
                $orderBy = $orderParam[0]['column'];
                $orderDir = $orderParam[0]['dir'];
                $orderByText="";
                switch(intval($orderBy)){
//                    case 0:
//                        $orderByText = "fecha_cita, hora_cita";
//                        break;
                    case 1:
                        $orderByText = "expediente";
                        break;
                    case 2:
                        $orderByText = "nombres";
                        break;
                    case 3:
                        $orderByText = "telefono";
                        break;
                    case 4:
                        $orderByText = "tratamiento";
                        break;
                }
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){
                    $sql = "SELECT suc.nombre as sucursal, CONCAT('<a class=\"citaEdit\" href=\"\" id=\"',c.id,'\">Editar cita</a>') as actions,c.id as id, CONCAT('<a class=\"link_expediente\" id=\"',exp.numero,'\">',exp.numero,'</a>') as expediente, CONCAT(DATE_FORMAT(c.fecha_cita, '%d-%m-%Y'),' ',DATE_FORMAT(c.hora_cita,'%H:%i')) as fecha, UPPER(CONCAT(per.nombres,' ',per.apellidos)) as nombres,per.telefono as tel, UPPER(trat.nombre) as tratamiento  FROM Cita c "
                            . "INNER JOIN paciente pac on(pac.id=c.paciente) "
                            . "INNER JOIN persona per ON(per.id=pac.persona) "
                            . "INNER JOIN tratamiento trat ON(trat.id=c.tratamiento) "
                            . "INNER JOIN expediente exp ON(exp.paciente=pac.id) "
                            . "INNER JOIN sucursal suc ON(suc.id=c.sucursal) "
                            . " WHERE suc.id=".$sucursal." c.fecha_cita BETWEEN '".$fechaInicio."' AND '".$fechaFin
                            . "' HAVING upper(CONCAT(expediente,' ',nombres,' ',telefono)) LIKE upper('%".$busqueda['value']."%') ";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $row['data'] = $stmt->fetchAll();
                    $row['recordsFiltered']= count($row['data']);
                    $sql = "SELECT suc.nombre as sucursal, CONCAT('<a class=\"citaEdit\" href=\"\" id=\"',c.id,'\">Editar cita</a>') as actions,c.id as id, CONCAT('<a class=\"link_expediente\" id=\"',exp.numero,'\">',exp.numero,'</a>') as expediente, CONCAT(DATE_FORMAT(c.fecha_cita, '%d-%m-%Y'),' ',DATE_FORMAT(c.hora_cita,'%H:%i')) as fecha, UPPER(CONCAT(per.nombres,' ',per.apellidos)) as nombres,per.telefono as tel, UPPER(trat.nombre) as tratamiento  FROM Cita c "
                            . "INNER JOIN paciente pac on(pac.id=c.paciente) "
                            . "INNER JOIN persona per ON(per.id=pac.persona) "
                            . "INNER JOIN tratamiento trat ON(trat.id=c.tratamiento) "
                            . "INNER JOIN expediente exp ON(exp.paciente=pac.id) "
                            . "INNER JOIN sucursal suc ON(suc.id=c.sucursal) "
                            . " WHERE suc.id=".$sucursal." c.fecha_cita BETWEEN '".$fechaInicio."' AND '".$fechaFin
                            . "' HAVING upper(CONCAT(expediente,' ',nombres,' ',telefono)) LIKE upper('%".$busqueda['value']."%') "
                            . "ORDER BY c.fecha_cita DESC, c.hora_cita DESC, ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $row['data'] = $stmt->fetchAll();
                }
                else{
                    
                    
                    $sql = "SELECT CONCAT('<a class=\"citaEdit\" href=\"\" id=\"',c.id,'\">Editar cita</a>') as actions,c.id as id, CONCAT('<a class=\"link_expediente\" id=\"',exp.numero,'\">',exp.numero,'</a>') as expediente, CONCAT(DATE_FORMAT(c.fechaCita, '%d-%m-%Y'),' ', DATE_FORMAT(c.horaCita,'%H:%i')) as fecha, UPPER(CONCAT(per.nombres,' ',per.apellidos)) as nombres,per.telefono as tel, UPPER(trat.nombre) as tratamiento, suc.nombre as sucursal  FROM DGPlusbelleBundle:Cita c "
                            . "JOIN c.paciente pac "
                            . "JOIN pac.persona per "
                            . "JOIN c.tratamiento trat "
                            . "JOIN pac.expediente exp "
                            . "JOIN c.sucursal suc "
                            . " WHERE suc.id =:sucursal AND c.fechaCita BETWEEN :fechaInicio AND :fechaFin "
                            . "ORDER BY c.fechaCita DESC, c.horaCita DESC, ". $orderByText." ".$orderDir;
                    $row['data'] = $em->createQuery($sql)
                            ->setParameters(array('sucursal'=>$sucursal, 'fechaInicio'=> $fechaInicio,'fechaFin'=> $fechaFin))
                            ->setFirstResult($start)
                            ->setMaxResults($longitud)
                            ->getResult();
                    
                    
//                    $sql = "SELECT pac.fecha_nacimiento as fechaNacimiento, pac.id, concat(per.nombres,' ',per.apellidos) as  nombres, per.telefono as telefono,per.telefono2 FROM paciente pac "
//                    . "INNER JOIN persona per on (per.id=pac.persona)"
//                    . " WHERE Month(pac.fecha_nacimiento) = Month('".$dateString."') "
//                    . " AND Day(pac.fecha_nacimiento) = Day('".$dateString."') "
//                    . " HAVING upper(CONCAT(fechaNacimiento,' ',nombres,' ',telefono)) LIKE upper('%".$busqueda['value']."%') "
//                    . "ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
//                    $stmt = $em->getConnection()->prepare($sql);
//                    $stmt->execute();
//                    $row['data'] = $stmt->fetchAll();
                }
                
                return new Response(json_encode($row));
            } catch (\Exception $e) {  
                var_dump($e);
                if(method_exists($e,'getErrorCode')){ 
                    switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $serverOffline= $this->getParameter('app.serverOffline');
                            $row['data'][0]['name'] =$serverOffline.'. CODE: '.$e->getErrorCode();
                        break;
                        default :
                            $row['data'][0]['name'] = $e->getMessage();                           
                        break;
                    }
                    $row['data'][0]['chk'] ='';
                    
                    $row['recordsFiltered']= 0;
                    }
                    else{
                            $data['error']=$e->getMessage();
                    }
                return new Response(json_encode($row));
        }
    }
    
    
    
    /**
     * @Route("/paquete/get/pendientes", name="admin_paquetes_pendiente", options={"expose"=true})
     * @Method("GET")
     */
    public function paquetesPendientesAction(Request $request){
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();
        $idPaciente = $request->get('param1');
        
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        $paquetes = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->findBy(array('paciente'=>$paciente->getPersona()->getId()));
        $data['data'] = "";
        $paquetesPendientesAux = array();
        $paquetesPendientesId = array();
        $paquetesPendientesNombre = array();
        foreach($paquetes as $key=>$paq){
            //var_dump($paq->getId());
            $detallePaquetes = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findBy(array('ventaPaquete'=>$paq->getId()));
            $sesionesPaquetes = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findBy(array('idVentaPaquete'=>$paq->getId()));
//            var_dump($detallePaquetes);
//            var_dump($sesionesPaquetes);
            foreach ($detallePaquetes as $k =>$detPaq){
//                var_dump($k);
                //var_dump($detPaq->getNumSesiones().'-'.$sesionesPaquetes[$k]->getNumSesion());
                if($detPaq->getNumSesiones()!=$sesionesPaquetes[$k]->getNumSesion()){
                    if(!in_array($paq->getId(), $paquetesPendientesAux)){
                        array_push($paquetesPendientesAux, $paq->getId());
                        array_push($paquetesPendientesId, $paq->getId());
                        array_push($paquetesPendientesNombre, $paq->getPaquete()->getNombre());
                        
                    }
                }
            }
                
//            var_dump($detallePaquetes->getNumSesiones());
//            var_dump($sesionesPaquetes->getNumSesion());
            
        }
        $data['id']=$paquetesPendientesId;  
        $data['nombre']=$paquetesPendientesNombre;
        
        $response->setData($data); 
        return $response;
        //return new Response(json_encode($paquetesPendientes));
        
        
    }
    
    /////Tratamientos pendientes del paquete
    /**
     * @Route("/tratamiento/get/pendientes", name="admin_tratamientos_pendiente", options={"expose"=true})
     * @Method("GET")
     */
    public function tratamientosPendientesPaqueteAction(Request $request){
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();
        $idPaquete = $request->get('param1');
        
        $idtratamientos = array();
        
        $tratamientos = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findBy(array('ventaPaquete' => $idPaquete));
        
        foreach ($tratamientos as $trat){
            $idtrat = $trat->getTratamiento()->getId();
            $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findOneBy(array('tratamiento' => $idtrat,
                                                                                                    'idVentaPaquete' => $idPaquete
                                                                                                ));
            if($seguimiento->getNumSesion() < $trat->getNumSesiones()){
                array_push($idtratamientos, $idtrat);
            }
        }
        
        $dql = "SELECT t.id as id, t.nombre as nombre FROM DGPlusbelleBundle:Tratamiento t "
                    . "WHERE t.id IN (:ids) ";
        $data = $em->createQuery($dql)
                       ->setParameter('ids', $idtratamientos)
                       ->getResult();
//        var_dump($data);
//        die();
        $response->setData($data); 
        return $response;
        //return new Response(json_encode($paquetesPendientes));
        
        
    }
    
    
    /////Tratamientos pendientes SIN paquete
    /**
     * @Route("/cita/get/editar/", name="admin_cita_editar_trat", options={"expose"=true})
     * @Method("GET")
     */
    public function citaEditarAction(Request $request){
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();
        $idCita= $request->get('param1');
        
        $cita= $em->getRepository('DGPlusbelleBundle:Cita')->find($idCita);
                
        $data['id']=$cita->getId();
        $data['pacienteId']=$cita->getPaciente()->getId();
        $data['paciente']=  ucwords(strtolower($cita->getPaciente()->getPersona()->getNombres(). ' '. $cita->getPaciente()->getPersona()->getApellidos()));
        $data['medico']=  ucwords(strtolower($cita->getEmpleado()->getPersona()->getNombres(). ' '. $cita->getEmpleado()->getPersona()->getApellidos()));
        $data['sucursal']=$cita->getSucursal()->getNombre();
        $data['fecha']=$cita->getFechaCita()->format('d-m-Y');
        $data['horaInicio']=$cita->getHoraCita()->format('H:i');
        $data['horaFin']=$cita->getHoraFin()->format('H:i');
        $data['descripcion']=$cita->getDescripcion();
        $data['estado']=$cita->getEstado();
        
        $data['citaPor']=$cita->getTipoCita();
        $data['paquete']=$cita->getPaquete();
        $data['tratamiento1']=$cita->getTratamiento1();
        $data['tratamiento2']=$cita->getTratamiento2();
               
        $response->setData($data); 
        return $response;
        //return new Response(json_encode($paquetesPendientes));
        
        
    }
    
    
    
    /////Tratamientos pendientes SIN paquete
    /**
     * @Route("/tratamiento/get/pend/", name="admin_tratamientos_pendiente_no_paquete", options={"expose"=true})
     * @Method("GET")
     */
    public function tratamientosPendientesAction(Request $request){
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();
        $idPaciente= $request->get('param1');
        
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        $tratamientos= $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->findBy(array('paciente'=>$paciente->getPersona()->getId()));
        //var_dump($tratamientos);
        $data['data'] = "";
        $paquetesPendientesAux = array();
        $paquetesPendientesId = array();
        $paquetesPendientesNombre = array();
        foreach($tratamientos as $key=>$paq){
            //var_dump($paq->getId());
            //$detallePaquetes = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findBy(array('idPersonaTratamiento'=>$paq->getId()));
            $sesionesPaquetes = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findBy(array('idPersonaTratamiento'=>$paq->getId()));
            //var_dump($sesionesPaquetes);
            
            if($paq->getNumSesiones()!=$sesionesPaquetes[0]->getNumSesion()){
                if(!in_array($paq->getId(), $paquetesPendientesAux)){
                    array_push($paquetesPendientesAux, $paq->getId());
                    array_push($paquetesPendientesId, $paq->getId());
                    array_push($paquetesPendientesNombre, $paq->getTratamiento()->getNombre());

                }
            }
            
                
//            var_dump($detallePaquetes->getNumSesiones());
//            var_dump($sesionesPaquetes->getNumSesion());
            
        }
        $data['id']=$paquetesPendientesId;  
        $data['nombre']=$paquetesPendientesNombre;
        
        $response->setData($data); 
        return $response;
        //return new Response(json_encode($paquetesPendientes));
        
        
    }
    
    
    /////Actualizar cita
    /**
     * @Route("/cita/editar/estadoytrat/", name="admin_cita_estado_edit_trat", options={"expose"=true})
     * @Method("POST")
     */
    public function citaEditEstadoTratAction(Request $request){
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();
        $citaId = $request->get('param1');
        $citaPor = $request->get('param2');
        $citaEstado= $request->get('param3');
        $citaConsulta = $request->get('param4');
        $citaPaquete= $request->get('param5');
        $citaTrat1= $request->get('param6');
        $citaTrat2= $request->get('param7');
        
        //var_dump($request);
//        var_dump($citaId);
//        var_dump($citaPor);
//        var_dump($citaEstado);
//        var_dump($citaConsulta);
//        var_dump($citaPaquete);
//        var_dump($citaTrat1);
//        var_dump($citaTrat2);
        
        date_default_timezone_set('America/El_Salvador');

        
        $citaObj = $em->getRepository('DGPlusbelleBundle:Cita')->find($citaId);
        
        
        
        
        $citaObj->setTipoCita($citaPor);
        
        $tratamiento = $em->getRepository('DGPlusbelleBundle:Tratamiento')->find($citaConsulta);
        $citaObj->setTratamiento($tratamiento);
        
        $citaObj->setEstado($citaEstado);
        
       
        
        switch($citaPor){
            case 1://///Consultas
                
                $citaObj->setTratamiento1(null);
                $citaObj->setTratamiento2(null);
                
                break;
            case 2://///Tratamientos sin paquetes
                
                $citaObj->setTratamiento1($citaTrat1);
                $citaObj->setTratamiento2($citaTrat2);
                
                break;
            case 3://///Tratamientos de paquetes
                
                $citaObj->setTratamiento1($citaTrat1);
                $citaObj->setTratamiento2($citaTrat2);
                
                break;
        }
        
        $em->merge($citaObj);
        $em->flush();
        
        $data['msg'] = "Cita modificada";
        $response->setData($data); 
        return $response;
        //return new Response(json_encode($paquetesPendientes));

    }
    
    
}
