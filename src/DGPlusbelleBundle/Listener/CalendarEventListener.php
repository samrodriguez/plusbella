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
        //var_dump($sucursal);
        //$em = $this->getDoctrine()->getManager();
        //var_dump($sucursal);
        if(isset($sucursal)){
            $citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findBy(array('sucursal'=>$sucursal));
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
            $idPaciente = $companyEvent->getPaciente()->getId();
            //var_dump($idPaciente);
            //$expediente = $this->em->getRepository('DGPlusbelleBundle:Expediente')->findBy(array('paciente'=>$idPaciente));
            
            $dql = "SELECT exp"
                . " FROM DGPlusbelleBundle:Expediente exp"
                . " WHERE exp.paciente =:paciente";
            $expediente= $this->em->createQuery($dql)
                    ->setParameter('paciente',$idPaciente)
                     ->getResult();
            
            //var_dump($expediente);
            $expNumero= $expediente[0]->getNumero();
            //var_dump($expNumero);
            $eventEntity = new EventEntity('', $st,$nh );
            
        
            
            //optional calendar event settings

            //var_dump($index);
            $eventEntity->setId($companyEvent->getID());
            $title = strtoupper($expNumero).' - '.$companyEvent->getPaciente()->getPersona()->getNombres().' '.$companyEvent->getPaciente()->getPersona()->getApellidos();
            
            $eventEntity->setAllDay(false); // default is false, set to true if this is an all day event
            $unicode="&#10003;";
            switch($companyEvent->getEstado()){
                case "A":    //Asistida
                    //$eventEntity->setBgColor('#48CFAD'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    $title = '<div class="fa fa-check"></div> | '.$title/*' | '.$companyEvent->getEstado().*/;
                    break;
                case "P":   //Pendiente
                    //$eventEntity->setBgColor('#4FC1E9'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    $title = '<div class="fa fa-calendar-minus-o"></div> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case "C":    //Cancelada
                    //$eventEntity->setBgColor('#ED5565'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    $title = '<div class="fa fa-calendar-times-o"></div> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case "N":    //Cancelada
                    //$eventEntity->setBgColor('#ED5565'); //set the background color of the event's label
                    //$eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    $title = '<div class="fa fa-calendar-o"></div> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
            }
                    
                    
                    
            switch($companyEvent->getTratamiento()->getCategoria()->getId()){
                case 1:    //Corporal
                    $eventEntity->setBgColor('#ED5565'); //set the background color of the event's label
                    $eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-check"> | '.$title/*' | '.$companyEvent->getEstado().*/;
                    break;
                case 2:      //Facial
                    $eventEntity->setBgColor('#AC92EC'); //set the background color of the event's label
                    $eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-certificate"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
                case 3:    //Cirugía
                
                    $eventEntity->setBgColor('#ED5565'); //set the background color of the event's label
                    $eventEntity->setFgColor('#000'); //set the foreground color of the event's label
                    //$title = '</div><div class="fa fa-close"> | '.$title/*' | '.$companyEvent->getEstado(). */;
                    break;
            }
            
            

            
            $eventEntity->setTitle($title);
            //$eventEntity->setUrl($unicode);
            //$eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
            //var_dump($eventEntity);
            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
    
    
    
    
    
    
    
}