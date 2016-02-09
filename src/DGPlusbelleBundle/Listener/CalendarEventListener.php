<?php
// src/Acme/DemoBundle/EventListener/CalendarEventListener.php  

namespace DGPlusbelleBundle\Listener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;

class CalendarEventListener
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {   
        
        $request = $calendarEvent->getRequest();
        $filter = $request->get('filter');
        $startDate = $calendarEvent->getStartDatetime();
        $endDate   = $calendarEvent->getEndDatetime();
        $sucursal = $request->get('sucursal');
        $user = $request->get('user');
        //var_dump($sucursal);
        //var_dump($user);
        //die();
        //$em = $this->getDoctrine()->getManager();
        //var_dump($sucursal);
        if(isset($sucursal)){
            if($user==0){
                $citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('sucursal'=>$sucursal));
            }
            else{
                $citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('sucursal'=>$sucursal,'empleado'=>$user));
            }
            
        }
        else{
            $citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findAll();
        }
        
        foreach($citas as $key => $companyEvent) {
            // create an event with a start/end time, or an all day event
            //var_dump($key);
            $fi = $companyEvent->getFechaCita()->format('Y-m-d');
            $ih = $companyEvent->getHoraCita()->format('H:i');
            
            //$fh = $companyEvent->getHoraFin()->format('H:i');
            $h = date("H:i", strtotime('+30 minutes', strtotime($ih)));
            
            //var_dump($h);
            
            
            $st    = new \DateTime($fi.' '.$ih);
            $nh    = new \DateTime($fi.' '.$h);
            //$//end   = new \DateTime($fi.' '.$fh);
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
		$title = strtoupper($expNumero).' - '.$companyEvent->getPaciente()->getPersona()->getNombres().' '.$companyEvent->getPaciente()->getPersona()->getApellidos().' | '.$doctor.' | '.$horaFin;
                //var_dump($expNumero);
            }
            else{
                $eventEntity->setId($companyEvent->getID());
                $idPaciente = "Reserva de cita";
                $expNumero ="";
//                $title = $idPaciente.$expNumero;
                $title = $idPaciente.$expNumero.' | '.$doctor.' | '.$horaFin;
            }
            //var_dump($idPaciente);
            //$expediente = $this->em->getRepository('DGPlusbelleBundle:Expediente')->findBy(array('paciente'=>$idPaciente));
            
            
            
            
        
            
            
            
            $eventEntity->setAllDay(false); // default is false, set to true if this is an all day event
            $unicode="&#10003;";
            
                    
                    
                    
            switch($companyEvent->getTratamiento()->getCategoria()->getId()){
                case 1:     //Categoria 1
                    $eventEntity->setBgColor('#8C1821'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-check"> | '.$title/*' | '.$companyEvent->getEstado().*/;
                    break;
                case 2:     //Categoria 2
                    $eventEntity->setBgColor('#4C4B31'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-certificate"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case 3:    //Categoria 3
                    $eventEntity->setBgColor('#C7931C'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case 4:    //Categoria 4
                    $eventEntity->setBgColor('#B2B064'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case 5:    //Categoria 5
                    $eventEntity->setBgColor('#7E9499'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case 6:    //Categoria 6
                    $eventEntity->setBgColor('#7C591A'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case 7:    //Categoria 7
                    $eventEntity->setBgColor('#D77C87'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case 8:    //Categoria 8
                    $eventEntity->setBgColor('#5D5D5D'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case 9:    //Categoria 9
                    $eventEntity->setBgColor('#E07140'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case 10:    //Categoria 10
                    $eventEntity->setBgColor('#0F6D38'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                
                
                case 11:    //Categoria 11
                    $eventEntity->setBgColor('#1C3343'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                
                case 12:    //Categoria 12
                    $eventEntity->setBgColor('#67A59B'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                
                case 13:    //Categoria 13
                    $eventEntity->setBgColor('#A053A0'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                
                default:    //Categoria 14+
                    $eventEntity->setBgColor('#AC92EC'); //set the background color of the event's label
                    $eventEntity->setFgColor('#FFF'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
            }
            
            
            switch($companyEvent->getEstado()){
                case "A":    //Asistida
                    //$eventEntity->setBgColor('#48CFAD'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    
                    $title = '<div class="fa fa-calendar-check-o" style="width: 17px; height: 100%; float: left; background: #69BD45;  position: absolute; margin-left: -3px; padding-left:2px "></div> <div style="width: 91%; height: 100%; float: right; position: relative; "> | '.$title.'</div>'/*' | '.$companyEvent->getEstado().*/;
                    break;
                case "P":   //Pendiente
                    //$eventEntity->setBgColor('#4FC1E9'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    $title = '<div class="fa fa-calendar-minus-o" style="width: 17px; height: 100%; float: left; background: #3852A4;  position: absolute; margin-left: -3px; padding-left:2px "></div> <div style="width: 91%; height: 100%; float: right; position: relative; "> | '.$title.'</div>'/*' | '.$companyEvent->getEstado(). */;
                    break;
                case "C":    //Cancelada
                    //$eventEntity->setBgColor('#ED5565'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    $title = '<div class="fa fa-calendar-times-o" style="width: 17px; height: 100%; float: left; background: #F00;  position: absolute; margin-left: -3px; padding-left:2px "></div> <div style="width: 91%; height: 100%; float: right; position: relative; "> | '.$title.'</div>'/*' | '.$companyEvent->getEstado(). */;
                    break;
                case "N":    //No asistida
                    //$eventEntity->setBgColor('#ED5565'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    $title = '<div class="fa fa-calendar-o" style="width: 17px; height: 100%; float: left; background: #421E5B;  position: absolute; margin-left: -3px; padding-left:2px "></div> <div style="width: 91%; height: 100%; float: right; position: relative; "> | '.$title.'</div>'/*' | '.$companyEvent->getEstado(). */;
                    break;
            }

            
            $eventEntity->setTitle($title);
            //$eventEntity->setUrl($unicode);
            //$eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
            //$eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
            //var_dump($eventEntity);
            //finally, add the event to the CalendarEvent for displaying on the calendar
            
            $calendarEvent->addEvent($eventEntity);
        }
    }
    
    
    
    
    
    
    
}