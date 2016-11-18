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
use DGPlusbelleBundle\Entity\SesionVentaTratamiento;
use DGPlusbelleBundle\Entity\SesionTratamiento;

/**
 * @Route("/admin/sesion-asistida")
 */
class SesionCitaAsistidaController extends Controller  
{
    /**
     * @Route("/evaluar-sesiones-cita", name="evaluar_sesiones_cita", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function evaluarSesionesCitaAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                date_default_timezone_set('America/El_Salvador');
                $em = $this->getDoctrine()->getManager();
                $registroSesionCita = 0;
                
                $idCita = $request->get('id');
                $cita = $em->getRepository('DGPlusbelleBundle:Cita')->find($idCita);                
                $tipoCita = $cita->getTipoCita();
                //$estadoCita = $cita->getEstado();
                
                //if($estadoCita != 'A') {
                    if(!is_null($tipoCita) && $tipoCita > 0) {
                        switch ($tipoCita){
                            case 1:
                                $registroSesionCita = $this->evaluarVentaTratamiento($em, $cita);
                                break;
                            case 2:
                                $registroSesionCita = $this->evaluarVentaPaquete($em, $cita);
                                break;
                        }
                    } else {
                        $registroSesionCita = 1;
                    }

                    if($registroSesionCita == 1) {
                        $mensaje = '¡Cita registrada como asistida satisfactoriamente!';
                    } else {
                        $mensaje = '¡Cambio de cita a asistida y sesiones de tratamiento registrados satisfactoriamente !';
                    }

                    $cita->setEstado('A');
                    $em->merge($cita);
                    $em->flush();
                /*} else {
                    $mensaje = '¡Error, Cita se encuentra como asistida, no puede ser modificada!';
                }*/
                
                $response = new JsonResponse();
                $response->setData(array(
                                    'regs' => $registroSesionCita,
                                    'mensaje' => $mensaje,
                                ));  
            
                return $response; 
                
            } catch (\Exception $ex) {  
                $response = new JsonResponse();
                $response->setData(array(
                                    'regs' => 0,
                                    'mensaje' => '¡La cita no pudo modificarse, intente de nuevo!',
                                ));  
            }                                
        } else {    
            return new Response('0');              
        }
    }
    
    private function evaluarVentaTratamiento($em, $entity) {
        $accion = 0;
        
        if(!is_null($entity->getTratamiento1())) {
            if($entity->getTratamiento1() > 0){
                $ventaTratamiento1 = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($entity->getTratamiento1());
                $segTratamiento1 = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findOneBy(array('idPersonaTratamiento' => $ventaTratamiento1));        

                if(($ventaTratamiento1->getNumSesiones() - $segTratamiento1->getNumSesion()) > 0) {
                    $sesionTratamiento1 = new SesionVentaTratamiento();
                    $sesionTratamiento1->setEmpleado($entity->getEmpleado());
                    $sesionTratamiento1->setSucursal($entity->getSucursal());
                    $sesionTratamiento1->setPersonaTratamiento($ventaTratamiento1);
                    $sesionTratamiento1->setFechaSesion(new \DateTime('now'));

                    $em->persist($sesionTratamiento1);
                    $em->flush();

                    $segTratamiento1->setNumSesion($segTratamiento1->getNumSesion() + 1);
                    $em->merge($segTratamiento1);
                    $em->flush();

                    $accion = 2;
                } else {
                    $accion = 1;
                }
            } else {
                $accion = 1;
            }
        }
        
        if(!is_null($entity->getTratamiento2())) {
            if($entity->getTratamiento2() > 0){
                $ventaTratamiento2 = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($entity->getTratamiento2());
                $segTratamiento2 = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findOneBy(array('idPersonaTratamiento' => $ventaTratamiento2));

                if(($ventaTratamiento2->getNumSesiones() - $segTratamiento2->getNumSesion()) > 0) {
                    $sesionTratamiento2 = new SesionVentaTratamiento();
                    $sesionTratamiento2->setEmpleado($entity->getEmpleado());
                    $sesionTratamiento2->setSucursal($entity->getSucursal());
                    $sesionTratamiento2->setPersonaTratamiento($ventaTratamiento2);
                    $sesionTratamiento2->setFechaSesion(new \DateTime('now'));

                    $em->persist($sesionTratamiento2);
                    $em->flush();

                    $segTratamiento2->setNumSesion($segTratamiento2->getNumSesion() + 1);
                    $em->merge($segTratamiento2);
                    $em->flush();

                    $accion = 2;
                } else {
                    $accion = 1;
                }
            }
        }
        
        return $accion;
    }
    
    private function evaluarVentaPaquete($em, $entity) {
        $accion = 0;
        
        if(!is_null($entity->getPaquete())) {
            $ventaPaquete = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($entity->getPaquete());
                                                
            if(!is_null($entity->getTratamiento1())) {
                if($entity->getTratamiento1() > 0){
                    $detalleVenta1 = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findOneBy(array(
                                                                                                                'ventaPaquete' => $ventaPaquete->getId(),
                                                                                                                'tratamiento'  => $entity->getTratamiento1(),
                                                                                                            ));
                    $seguimiento1 = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findOneBy(array(
                                                                                                                'idVentaPaquete' => $entity->getPaquete(), 
                                                                                                                'tratamiento' => $detalleVenta1->getTratamiento()->getId()
                                                                                                            ));

                    //$tratamiento1 = $em->getRepository('DGPlusbelleBundle:Tratamiento')->find($entity->getTratamiento1());

                    if(($detalleVenta1->getNumSesiones() - $seguimiento1->getNumSesion()) > 0) {
                        $sesionTratamiento1 = new SesionTratamiento();

                        $sesionTratamiento1->setVentaPaquete($ventaPaquete);
                        $sesionTratamiento1->setFechaSesion(new \DateTime('now'));
                        $sesionTratamiento1->setEmpleado($entity->getEmpleado());
                        $sesionTratamiento1->setSucursal($entity->getSucursal());
                        $sesionTratamiento1->setTratamiento($detalleVenta1->getTratamiento());
                        $sesionTratamiento1->setRegistraReceta("0");

                        $em->persist($sesionTratamiento1);
                        $em->flush();

                        $seguimiento1->setNumSesion($seguimiento1->getNumSesion() + 1);
                        $em->merge($seguimiento1);
                        $em->flush();

                        $tratamientos = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findBy(array('ventaPaquete' => $ventaPaquete->getId()));

                        $aux = 0;
                        $total = count($tratamientos);
                        foreach ($tratamientos as $trat){
                             if($seguimiento1->getNumSesion() >= $trat->getNumSesiones()){
                                 $aux++;
                            }
                        }

                        if($aux < $total){
                            $ventaPaquete->setEstado(2);
                        } else {
                            $ventaPaquete->setEstado(3);
                        }

                        $em->merge($ventaPaquete);
                        $em->flush();

                        $accion = 2;
                    } else {
                        $accion = 1;
                    }
                } else {
                    $accion = 1;
                }
            } else {
                $accion = 1;
            }

            if(!is_null($entity->getTratamiento2())) {
                if($entity->getTratamiento2() > 0){
                    $detalleVenta2 = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findOneBy(array(
                                                                                                                'ventaPaquete' => $ventaPaquete->getId(),
                                                                                                                'tratamiento'  => $entity->getTratamiento2(),
                                                                                                            ));
                    $seguimiento2 = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findOneBy(array(
                                                                                                                'idVentaPaquete' => $entity->getPaquete(), 
                                                                                                                'tratamiento' => $detalleVenta2->getTratamiento()->getId()
                                                                                                            ));

                    if(($detalleVenta2->getNumSesiones() - $seguimiento2->getNumSesion()) > 0) {
                        $sesionTratamiento2 = new SesionTratamiento();

                        $sesionTratamiento2->setVentaPaquete($ventaPaquete);
                        $sesionTratamiento2->setFechaSesion(new \DateTime('now'));
                        $sesionTratamiento2->setEmpleado($entity->getEmpleado());
                        $sesionTratamiento2->setSucursal($entity->getSucursal());
                        $sesionTratamiento2->setTratamiento($detalleVenta2->getTratamiento());                
                        $sesionTratamiento2->setRegistraReceta("0");

                        $em->persist($sesionTratamiento2);
                        $em->flush();

                        $seguimiento2->setNumSesion($seguimiento2->getNumSesion() + 1);
                        $em->merge($seguimiento2);
                        $em->flush();

                        $tratamientos = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findBy(array('ventaPaquete' => $ventaPaquete->getId()));

                        $aux = 0;
                        $total = count($tratamientos);
                        foreach ($tratamientos as $trat){
                             if($seguimiento2->getNumSesion() >= $trat->getNumSesiones()){
                                 $aux++;
                            }
                        }

                        if($aux < $total){
                            $ventaPaquete->setEstado(2);
                        } else {
                            $ventaPaquete->setEstado(3);
                        }

                        $em->merge($ventaPaquete);
                        $em->flush();

                        $accion = 2;
                    } else {
                        $accion = 1;
                    }
                } else {
                    $accion = 1;
                }
            } else {
                $accion = 1;
            }
        } else {
            $accion = 1;
        }
        
                
        return $accion;
    }
}
