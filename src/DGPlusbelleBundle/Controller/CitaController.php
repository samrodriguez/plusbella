<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Cita;
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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Cita')->findAll();
        $sucursales= $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array('estado'=>true));
        $categorias = $em->getRepository('DGPlusbelleBundle:Categoria')->findAll();
        //var_dump($categorias);
        /*$dql = "SELECT exp.paciente"
                . "FROM DGPlusbelleBundle:Cita c, DGPlusbelleBundle:Paciente p, DGPlusbelleBundle:Expediente exp"
                . "WHERE c.id.paciente = p.id AND p.expediente AND exp.paciente = exp.id";
               
        $entities = $em->createQuery($dql)
                     ->getResult();
        
        var_dump($entities);
        */
        //var_dump($sucursales);
        return array(
            'entities' => $entities,
            'sucursales' => $sucursales,
            'categorias' => $categorias,
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
        $entity = new Cita();
        $entity->setFechaRegistro(new \DateTime('now'));
        $entity->setEstado('P');
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        
        $idEmpleado = $entity->getEmpleado()->getId();
        $horaCita = $entity->getHoraCita();
        $fechaCita = $entity->getFechaCita();
        
        //var_dump($entity->getEmpleado()->getId());
        //var_dump($entity->getHoraCita());
        //var_dump($idEmpleado);
        //var_dump($horaCita);
        //var_dump($fechaCita);
        //echo gmdate("H:i", $horaCita);
        //$horaCita = date_format($horaCita,'H:i');
        
        //$horaCita = strtotime($horaCita);
        //var_dump($horaCita);
        $em = $this->getDoctrine()->getManager();
        $cita = $em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('empleado'=>$idEmpleado,'horaCita'=>$horaCita,'fechaCita'=>$fechaCita));
        
        //var_dump($cita);
        if(count($cita)==0){
            if ($form->isValid()) {
                $usuario= $this->get('security.token_storage')->getToken()->getUser();
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->get('bitacora')->escribirbitacora("Se creo una nueva cita",$usuario->getId());
                return $this->redirect($this->generateUrl('admin_cita'));
            }
        }
        else{
            
            return array(
                'entity' => $entity,
                'form'   => $form->createView(),
                'mensaje'=> 'Ya hay una cita programada en ese fecha y hora, para el técnico que selecciono',
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
                                                        array('class'=>'btn btn-success btn-sm')));

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
        );
    }

    /**
     * Finds and displays a Cita entity.
     *
     * @Route("/{id}", name="admin_cita_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Cita')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cita entity.');
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
     * @Route("/{id}", name="admin_cita_update")
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
     * @Route("/{id}", name="admin_cita_delete")
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
        var_dump($horasExtraidas);
        return new Response(json_encode($horas));
    }
    
    
    
     /**
     * @Route("/nuevahora/get/{id}/{delta}/{fecha}", name="get_nuevaHora", options={"expose"=true})
     * @Method("GET")
     */
    public function nuevaHoraAction(Request $request, $id, $delta, $fecha) {
        
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Cita')->find($id);
        $empleado=$entity->getEmpleado();
        //var_dump($empleado->getId());
        //$entityDuplicada = $em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('empleado'=>$empleado->getId(),'horaInicio'=>$entity->getHoraInicio()));
        $horaInicial = $entity->getHoraCita();
        //var_dump($dql);
        $time = strtotime($fecha);
        //var_dump($entity->getFechaCita());
        $newformat = date('Y-m-d',$time);
        //var_dump($newformat);
        //var_dump( count( $entityDuplicada));
        $exito['regs']=0;
        if(isset($entity)){
            
                
            
                //var_dump($entity);
                $hora = $entity->getHoraCita()->format("H:i");

                $horaTime = strtotime($hora);
                $horaNueva = date("H:i", strtotime($delta.' minutes', $horaTime));
                //var_dump($horaNueva);
                $entity->setHoraCita(new \DateTime($horaNueva));
                //echo "hora nueva: ";
                //var_dump($entity->getHoraInicio());
                $dql = "SELECT c
                    FROM DGPlusbelleBundle:Cita c
                    WHERE c.empleado =:idEmp AND c.horaCita =:hora AND c.fechaCita=:fecha AND c.id <>:id";
                $entityDuplicada = $em->createQuery($dql)
                                    ->setParameters(array('idEmp'=>$empleado->getId(),'hora'=>$entity->getHoraCita()->format('H:i'),'fecha'=>$newformat,'id'=>$entity->getId()))
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
                    if ($fechaReprogramada < $today_dt) {
                        //var_dump($newformat);
                        //var_dump($expire_dt);
                        $exito['regs']=3;//Error, intenta reprogramar la cita a un dia anterior a "hoy"
                    }
                    else{
                        $var = date('H:i');
                        //var_dump($var);
                        
                        if($horaNueva>=$var ){
                        
                            if($entity->getEstado()=="P"){
                                $entity->setFechaCita(new \DateTime($newformat));
                                $em->persist($entity);
                                $em->flush();   
                                $exito['regs']=0; //Cita reprogramada con exito
                            }
                            else{
                                $exito['regs']=1;//Error, La cita tiene estado asistida o cancelada
                            }
                        }
                        else{
                            /*if ($fechaReprogramada > $today_dt) {
                                $entity->setFechaCita(new \DateTime($newformat));
                                $em->persist($entity);
                                $em->flush();   
                                $exito['regs']=0; //Cita reprogramada con exito
                            }*/
                            //else{
                                $exito['regs']=4;//Error, la hora es antes de la actual
                            //}
                            
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
                c.fechaCita, c.horaCita, c.estado
                    FROM DGPlusbelleBundle:Cita c
                    JOIN c.empleado emp
                    JOIN c.tratamiento t
                    JOIN c.paciente p
                    JOIN p.persona pac
                    JOIN emp.persona per
                    JOIN p.expediente exp                    
                WHERE c.id =:id";
        $cita['regs'] = $em->createQuery($dql)
                ->setParameter('id', $id)
                ->getArrayResult();
        
        //var_dump($cita);
        $cita['regs'][0]["numero"] = strtoupper($cita['regs'][0]["numero"]);
        $cita['regs'][0]["nombres"] = ucwords($cita['regs'][0]["nombres"]);
        $cita['regs'][0]["apellidos"] = ucwords($cita['regs'][0]["apellidos"]);
        $cita['regs'][0]["nombreTratamiento"] = ucwords($cita['regs'][0]["nombreTratamiento"]);
        $cita['regs'][0]["primerNombreEmp"] = ucwords($cita['regs'][0]["primerNombreEmp"]);
        $cita['regs'][0]["primerApellidoEmp"] = ucwords($cita['regs'][0]["primerApellidoEmp"]);
        $cita['regs'][0]["fechaCita"] = $cita['regs'][0]["fechaCita"]->format("d-m-Y");
        $cita['regs'][0]["horaCita"] = $cita['regs'][0]["horaCita"]->format("H:i");
        
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
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $dql = "SELECT c.id FROM DGPlusbelleBundle:Cita c WHERE c.empleado =:empleado AND c.fechaCita=:fecha AND c.horaCita=:hora ";
        $cita['regs'] = $em->createQuery($dql)
                ->setParameters(array('empleado'=>$idempleado,'fecha'=>$fecha,'hora'=>$hora))
                ->getArrayResult();
        
        //var_dump($cita);
        
        
        
        //var_dump($cita['regs'][0]["primerNombre"]);
        //var_dump($cita);
        
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
            $em->persist($entity);
            $em->flush();
            $cita['regs'] = 0;  //Cita encontrada y modificada con éxito
        }
        else{
            $cita['regs'] = 1;  //Cita no encontrada
        }
                 
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
        
        //var_dump($entity);
        if(count($entity)!=0){
            
            $entity->setEstado("A");
            $em->persist($entity);
            $em->flush();
            $cita['regs'] = 0;  //Cita encontrada y modificada con éxito
        }
        else{
            $cita['regs'] = 1;  //Cita no encontrada
        }
                 
        //var_dump($cita);
        
        //var_dump($cita['regs'][0]["primerNombre"]);
        //var_dump($cita);
        
        return new Response(json_encode($cita));
    }
    
}
