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
        
        //$em = $this->getDoctrine()->getManager();
        
        $citas = $this->em->getRepository('DGPlusbelleBundle:Cita')->findAll();
        /*$dql = "SELECT c,p,exp"
                . " FROM DGPlusbelleBundle:Cita c"
                . " JOIN c.paciente p"
                . " JOIN p.expediente exp";
         
        $entities = $this->em->createQuery($dql)
                     ->getResult();
        
        var_dump($entities[0]->getPaciente()->getExpediente());
        */
        foreach($citas as $key => $companyEvent) {
            // create an event with a start/end time, or an all day event
            //var_dump($key);
            $fi = $companyEvent->getFechaCita()->format('Y-m-d');
            $ih = $companyEvent->getHoraInicio()->format('H:i');
            
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
            
            $eventEntity->setTitle(strtoupper($expNumero).' - '.$companyEvent->getPaciente()->getPersona()->getPrimerNombre().' '.$companyEvent->getPaciente()->getPersona()->getPrimerApellido());
            $eventEntity->setAllDay(false); // default is false, set to true if this is an all day event
            $eventEntity->setBgColor('#48CFAD'); //set the background color of the event's label
            $eventEntity->setFgColor('#000'); //set the foreground color of the event's label
            //$eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels

            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}