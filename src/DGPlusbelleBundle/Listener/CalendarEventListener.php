<?php
// src/Acme/DemoBundle/EventListener/CalendarEventListener.php  

namespace DGPlusbelleBundle\Listener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;

class CalendarEventListener
{
    private $em;
    private $c;

    public function __construct(EntityManager $entityManager, $controller)
    {
        $this->em = $entityManager;
        $this->c = $controller;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {    
        
        $request = $calendarEvent->getRequest();
        $filter = $request->get('filter');
        //$startDate = $calendarEvent->getStartDatetime();
        //$endDate   = $calendarEvent->getEndDatetime();
        $sucursal = $request->get('sucursal');
        $hoyFiltro = $request->get('hoyFiltro');
//        $fechaInicio = $request->get('fechaInicio');
//        $fechaFin = $request->get('fechaFin');
        
        $request   = $calendarEvent->getRequest();
        $filter = $request->get('filter');
        
        $nombre = $request->get('paciente');
        
        $user = $request->get('user');
        
        $fechaInicio = $request->get('start');
        $fechaFin = $request->get('end');
        $startDate = date('Y-m-d',$fechaInicio);
        $endDate = date('Y-m-d',$fechaFin);
        
        //var_dump($startDate);
        //var_dump($endDate);
        //var_dump($nombre);
        
        
        if($sucursal==''){
        //    $sucursal =3;
        }
        //var_dump($sucursal);
        //die();
//        var_dump($fechaInicio);
//        var_dump($fechaFin);
//        die();
        //$fechaInicioDate = new \DateTime($fechaInicio);
        //$fechaFinDate = new \DateTime($fechaFin);
        
        
        //die();
        //$fechaInicio = $fechaInicioDate->format('Y-m-d');
        //$fechaFin = $fechaFinDate->format('Y-m-d');
        //var_dump($fechaInicio);
        //var_dump($fechaFin);
        //die();
        //$date = new \DateTime($hoyFiltro);
        //var_dump($hoyFiltro);
        
        //var_dump($sucursal);
        //var_dump($user);
        //die();
        //$em = $this->getDoctrine()->getManager();
        
//        var_dump($sucursal);
//        die();
        
        if($nombre!=''){
            $dql = "SELECT pac.id , per.nombres,per.apellidos FROM DGPlusbelleBundle:Paciente pac "
                    . "JOIN pac.persona per "
                    . "JOIN pac.expediente exp "
                    . "WHERE CONCAT(upper(per.nombres),' ',upper(per.apellidos),' ', upper(exp.numero)) LIKE upper(:nombre) ";
                
            $paciente = $this->em->createQuery($dql)
                        ->setParameters(array('nombre'=>'%'.$nombre.'%'))
                        ->getResult();
        }
        else{
            $paciente=1;
        }
        //var_dump($nombre);
        //var_dump($paciente);
        
        //die();
        if($sucursal!=''){
            //echo "sucursal ";
            if($user==0){
                //echo "user";
                if($nombre==''){
                    //echo " nombre";
                     $dql = "SELECT c FROM DGPlusbelleBundle:Cita c "
                        . "WHERE c.sucursal=:sucursal AND c.fechaCita BETWEEN :fechaInicio AND :fechaFin";
                
                    $citas = $this->em->createQuery($dql)
                        ->setParameters(array('sucursal'=>$sucursal,'fechaInicio'=>$startDate,'fechaFin'=>$endDate))
                         ->getResult();
                 //var_dump($citas);
                }
                else{     
                    if(count($paciente)!=0){
                        //echo $paciente[0]['id'];
                        //echo count($paciente);
                        $dql = "SELECT c FROM DGPlusbelleBundle:Cita c "
                            . "JOIN c.paciente pac "
                            . "JOIN pac.expediente exp "
                            . "JOIN pac.persona per "
                        . "WHERE c.sucursal=:sucursal AND c.fechaCita BETWEEN :fechaInicio AND :fechaFin AND CONCAT(upper(per.nombres),' ',upper(per.apellidos),' ', upper(exp.numero)) LIKE upper(:nombre) ";
                        $citas = $this->em->createQuery($dql)
                            ->setParameters(array('sucursal'=>$sucursal,'fechaInicio'=>$startDate,'fechaFin'=>$endDate,'nombre'=>'%'.$nombre.'%'))
                            ->getResult();
                        //echo "prueba";
                    }
                    //var_dump($citas);
                }
                
//                $citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('sucursal'=>$sucursal));
            }
            else{
                //$citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('sucursal'=>$sucursal));
                if($nombre==''){
                $dql = "SELECT c FROM DGPlusbelleBundle:Cita c "
                        . "WHERE c.sucursal=:sucursal AND c.empleado=:user AND c.fechaCita BETWEEN :fechaInicio AND :fechaFin";
                
                $citas = $this->em->createQuery($dql)
                        ->setParameters(array('sucursal'=>$sucursal,'user'=>$user,'fechaInicio'=>$startDate,'fechaFin'=>$endDate))
                         ->getResult();
                }
                else{
//                    $dql = "SELECT c FROM DGPlusbelleBundle:Cita c "
//                        . "WHERE c.sucursal=:sucursal AND c.fechaCita BETWEEN :fechaInicio AND :fechaFin AND c.paciente=:id";
//                    if(count($paciente)!=0){
//                        $citas = $this->em->createQuery($dql)
//                        ->setParameters(array('sucursal'=>$sucursal,'fechaInicio'=>$startDate,'fechaFin'=>$endDate,'id'=>$paciente[0]['id']))
//                         ->getResult();
//                    }
                    
                    $dql = "SELECT c FROM DGPlusbelleBundle:Cita c "
                            . "JOIN c.paciente pac "
                            . "JOIN pac.expediente exp "
                            . "JOIN pac.persona per "
                        . "WHERE c.sucursal=:sucursal AND c.empleado=:empleado AND c.fechaCita BETWEEN :fechaInicio AND :fechaFin AND CONCAT(upper(per.nombres),' ',upper(per.apellidos),' ', upper(exp.numero)) LIKE upper(:nombre) ";
                        $citas = $this->em->createQuery($dql)
                            ->setParameters(array('sucursal'=>$sucursal,'empleado'=>$user,'fechaInicio'=>$startDate,'fechaFin'=>$endDate,'nombre'=>'%'.$nombre.'%'))
                            ->getResult();
                        
                }
                //$citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('sucursal'=>$sucursal,'empleado'=>$user));
            }
            
        }
        else{
            //echo "sucursal todas";
            //$citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('fechaCita'=>$date));
            if($user==0){
                if($nombre==''){
                    $citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findAll();
                }else{
                    $citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('paciente'=>$paciente[0]['id']));
                }
                    
            }
            else{
                if($nombre==''){
                    $citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('empleado'=>$user));
                }else{
                    $citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('empleado'=>$user,'paciente'=>$paciente[0]['id']));
                }
                //$citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('sucursal'=>$sucursal));
                
            }
        }
        //die();
        //var_dump($citas);
        $cierres = $this->em->getRepository('DGPlusbelleBundle:CierreAdministrativo')->findAll();
        
        
        foreach($cierres as $key => $cierreEvent) {
            $fi = $cierreEvent->getFecha()->format('Y-m-d');
            $ih = $cierreEvent->getHoraInicio()->format('H:i');
            $fh = $cierreEvent->getHoraFin()->format('H:i');
            
            $st    = new \DateTime($fi.' '.$ih);
            $nh    = new \DateTime($fi.' '.$fh);
            
            
            
            $eventEntity = new EventEntity('', $st,$nh );
            
            $empleado = $cierreEvent->getEmpleado()->getPersona()->getNombres().' '.$cierreEvent->getEmpleado()->getPersona()->getApellidos();
            $motivo=$cierreEvent->getMotivo();
            
            $title = strtoupper("Cierre administrativo | ".$empleado.' | '.$motivo);
            $title = '<div class="col-xs-1 fa fa-lock fa-2" style="border:1px solid #F00; border-right:0px solid #F00; color:#FFF; height: 100%; float: left; background: #69BD45;  position: absolute; padding-left:2px "></div> <div class="col-xs-11" style="border-left:0px solid #000 !important; border:1px solid #F00;width: 93%; height: 100%; float: right; position: relative; "> | '.$title.'</div>'/*' | '.$companyEvent->getEstado().*/;
            $eventEntity->setTitle($title);
            
            $eventEntity->setBgColor('#FFF'); //set the background color of the event's label
            $eventEntity->setFgColor('#000');
            
            $calendarEvent->addEvent($eventEntity);
        }
        
        $fechaHoy = date("Y-m-d");
        //var_dump($this->c->getTransport());
        if(count($paciente)!=0){
        foreach($citas as $key => $companyEvent) {
            // create an event with a start/end time, or an all day event
            //var_dump($key);
            $fi = $companyEvent->getFechaCita()->format('Y-m-d');
            $ih = $companyEvent->getHoraCita()->format('H:i');
            
            if($fi==$fechaHoy){
                //var_dump($request->get($title));
                //die();
                
                //Claro
                //$this->c->sendEmail("77456982@sms.claro.com.sv","","","","Recordatorio de cita el dia: ".$fi." a las ".$ih);
                
                //Tigo 
                //$this->c->sendEmail("75061915@sms.claro.com.sv","","","","Recordatorio de cita el dia: ".$fi." a las ".$ih);
                
                //Movistar, este no funciona
                //$this->c->sendEmail("71727845@sms.claro.com.sv","","","","Recordatorio de cita el dia: ".$fi." a las ".$ih);
                
                //Digicel
                //$this->c->sendEmail("xxxxxxxx@digimensajes.com","","","","Recordatorio de cita el dia: ".$fi." a las ".$ih);
                
                
                
            }
            
            //$fh = $companyEvent->getHoraFin()->format('H:i');
            $h = date("H:i", strtotime('+30 minutes', strtotime($ih)));
            
            //var_dump($h);
            
            //$this->get('envio_correo')->sendEmail("77456982@sms.claro.com.sv","","","","Recordatorio de cita el dia: ");
            //$st    = new \DateTime($fi.' '.$ih);
            //$nh    = new \DateTime($fi.' '.$h);
            //$//end   = new \DateTime($fi.' '.$fh);
            //$eventEntity = new EventEntity('', $st,$nh );
            $ih = $companyEvent->getHoraCita()->format('H:i');
            $fh = $companyEvent->getHoraFin()->format('H:i');
            
            $st    = new \DateTime($fi.' '.$ih);
            $nh    = new \DateTime($fi.' '.$fh);
            
            $eventEntity = new EventEntity('', $st,$nh );
            
            //var_dump($companyEvent);
            //echo $companyEvent->getHoraFin()=='00:00';
            if($companyEvent->getHoraFin()->format("H:i")=='00:00'){
                //var_dump($companyEvent);
                $horaFin = $companyEvent->getHoraCita()->format("H:i");
                $horaFinTime = strtotime($horaFin);
                $horaFin = date("g:i a", strtotime('30 minutes', $horaFinTime));
            }
            else{
                $horaFin = $companyEvent->getHoraFin()->format("H:i");
                $horaFinTime = strtotime($horaFin);
                //$horaFin = date("g:i a", strtotime($horaFinTime));
            }
            //echo $horaFin;
            
            if($companyEvent->getPaciente()!=null){
                $idPaciente = $companyEvent->getPaciente()->getId();
                $dql = "SELECT exp"
                . " FROM DGPlusbelleBundle:Expediente exp"
                . " WHERE exp.paciente =:paciente";
                $expediente= $this->em->createQuery($dql)
                        ->setParameter('paciente',$idPaciente)
                         ->getResult();

                //var_dump($expediente);
                if(count($expNumero = $expediente)!=0){
                    $expNumero= $expediente[0]->getNumero();
                }
                else{
                    $expNumero="";
                }
                //optional calendar event settings

		$doctor = $companyEvent->getEmpleado()->getPersona()->getNombres().' '.$companyEvent->getEmpleado()->getPersona()->getApellidos();
                //var_dump($index);
                $eventEntity->setId($companyEvent->getID());
//                $title = strtoupper($expNumero).' - '.$companyEvent->getPaciente()->getPersona()->getNombres().' '.$companyEvent->getPaciente()->getPersona()->getApellidos();
		$title = strtoupper($expNumero).' - '.strtoupper($companyEvent->getPaciente()->getPersona()->getNombres().' '.$companyEvent->getPaciente()->getPersona()->getApellidos()).' | '.$doctor.' | '.$horaFin;
                //var_dump($expNumero);
            }
            else{
                $eventEntity->setId($companyEvent->getID());
                $idPaciente = strtoupper("Reserva de cita");
                $expNumero ="";
//                $title = $idPaciente.$expNumero;
                $title = $idPaciente.$expNumero.' | '.strtoupper($doctor).' | '.$horaFin;
            }
            //var_dump($idPaciente);
            //$expediente = $this->em->getRepository('DGPlusbelleBundle:Expediente')->findBy(array('paciente'=>$idPaciente));
            
            
            
            
        
            
            
            
            $eventEntity->setAllDay(false); // default is false, set to true if this is an all day event
            $unicode="&#10003;";
            
                    
                    //Cita
            //$eventEntity->setBgColor('#C3C3C3'); //set the background color of the event's label
            //$eventEntity->setFgColor('#FFF'); //set color font 
            $eventEntity->setBgColor('#FFF'); //set the background color of the event's label
            $eventEntity->setFgColor('#000'); //     
            /*switch($companyEvent->getTratamiento()->getCategoria()->getId()){
                case 1:     //Categoria 1
                    $eventEntity->setBgColor('#8C1821'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-check"> | '.$title/*' | '.$companyEvent->getEstado()./;
                    break;
                case 2:     //Categoria 2
                    $eventEntity->setBgColor('#4C4B31'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-certificate"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                case 3:    //Categoria 3
                    $eventEntity->setBgColor('#C7931C'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                case 4:    //Categoria 4
                    //$eventEntity->setBgColor('#B2B064'); //set the background color of the event's label
                    $eventEntity->setBgColor('#EA92C3'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                case 5:    //Categoria 5
                    $eventEntity->setBgColor('#7E9499'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                case 6:    //Categoria 6
                    $eventEntity->setBgColor('#7C591A'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                case 7:    //Categoria 7
                    $eventEntity->setBgColor('#D77C87'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                case 8:    //Categoria 8
                    $eventEntity->setBgColor('#5D5D5D'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                case 9:    //Categoria 9
                    $eventEntity->setBgColor('#E07140'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                case 10:    //Categoria 10
                    $eventEntity->setBgColor('#0F6D38'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                
                
                case 11:    //Categoria 11
                    $eventEntity->setBgColor('#1C3343'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                
                case 12:    //Categoria 12
                    $eventEntity->setBgColor('#67A59B'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). /;
                    break;
                
                case 13:    //Categoria 13
                    $eventEntity->setBgColor('#A053A0'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). ;
                    break;
                
                default:    //Categoria 14+
                    $eventEntity->setBgColor('#AC92EC'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). ;
                    break;
            }
            */
            
            switch($companyEvent->getEstado()){
                case "A":    //Asistida
                    //$eventEntity->setBgColor('#48CFAD'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    
                    $title = '<div class="col-xs-1 fa fa-calendar-check-o" style="border:1px solid #C3C3C3; border-right:0px solid #F00; color:#FFF; height: 100%; float: left; background: #69BD45;  position: absolute; padding-left:2px "></div> <div class="col-xs-11" style="border-left:0px solid #000 !important; border:1px solid #C3C3C3;  height: 100%; float: right; position: relative; "> | '.$title.'</div>'/*' | '.$companyEvent->getEstado().*/;
                    break;
                case "P":   //Pendiente
                    //$eventEntity->setBgColor('#4FC1E9'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    $title = '<div class=" col-xs-1 fa fa-calendar-minus-o" style="border:1px solid #C3C3C3; border-right:0px solid #F00; color:#FFF; height: 100%; float: left; background: #3852A4;  position: absolute; padding-left:2px "></div> <div class="col-xs-11" style="border-left:0px solid #000 !important; border:1px solid #C3C3C3; height: 100%; float: right; position: relative; "> | '.$title.'</div>'/*' | '.$companyEvent->getEstado(). */;
                    break;
                case "C":    //Cancelada
                    //$eventEntity->setBgColor('#ED5565'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    $title = '<div class="col-xs-1 fa fa-calendar-times-o" style="border:1px solid #C3C3C3; border-right:0px solid #F00; color:#FFF; height: 100%; float: left; background: #F00;  position: absolute; padding-left:2px "></div> <div class="col-xs-11" style="border-left:0px solid #000 !important; border:1px solid #C3C3C3; height: 100%; float: right; position: relative; "> | '.$title.'</div>'/*' | '.$companyEvent->getEstado(). */;
                    break;
                case "N":    //No asistida
                    //$eventEntity->setBgColor('#ED5565'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    $title = '<div class="col-xs-1 fa fa-calendar-o" style="border:1px solid #C3C3C3; border-right:0px solid #F00; color:#FFF; height: 100%; float: left; background: #421E5B;  position: absolute; padding-left:2px "></div> <div class="col-xs-11" style="border-left:0px solid #000 !important; border:1px solid #C3C3C3; height: 100%; float: right; position: relative; "> | '.$title.'</div>'/*' | '.$companyEvent->getEstado(). */;
                    break;
            }

            
            $eventEntity->setTitle($title);
            //$eventEntity->setUrl($unicode);
            //$eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
            //$eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
            //var_dump($eventEntity);
            //finally, add the event to the CalendarEvent for displaying on the calendar
            
            $calendarEvent->addEvent($eventEntity);
        }// fin de foreach para citas
    }
        
    }
    
    
    
    
    
    
    
}