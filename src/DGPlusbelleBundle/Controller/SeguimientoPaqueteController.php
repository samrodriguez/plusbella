<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\SeguimientoPaquete;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * SeguimientoPaquete controller.
 *
 * @Route("/admin/seguimientopaquete")
 */
class SeguimientoPaqueteController extends Controller
{

    /**
     * Lists all SeguimientoPaquete entities.
     *
     * @Route("/", name="admin_seguimientopaquete")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a SeguimientoPaquete entity.
     *
     * @Route("/{id}", name="admin_seguimientopaquete_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SeguimientoPaquete entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
    
    /**
    * Ajax utilizado para buscar las sesiones de tratamiento de un paquete vendido
    *  
    * @Route("/buscarInfPaqueteVendido", name="admin_busqueda_paquete_seguimiento")
    */
    public function buscarInformacionPaqueteAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $ventaPaqueteId = $this->get('request')->request->get('id');
            
            $rsm = new ResultSetMapping();
            $rsm2 = new ResultSetMapping();
            $em = $this->getDoctrine()->getManager();            
                
            $sql = "select concat_ws(' ', emp.nombres, emp.apellidos) as empleado, "
                    . "concat_ws(' ', pac.nombres, pac.apellidos) as paciente, "
                    . "exp.numero as numExp, "
                    . "paq.nombre as nomPaquete, "
                    . "paq.costo as costoPaquete, "
                    . "ven.fecha_venta as venta, "
                    . "tra.nombre as ntrata, "
                    . "des.porcentaje as porcentaje, "
                    . "pt.num_sesiones as sesiones, "
                    . "seg.num_sesion as numSesion "
                    . "from venta_paquete ven "
                    . "inner join paquete paq on ven.paquete = paq.id "
                    . "inner join seguimiento_paquete seg on ven.id = seg.id_venta_paquete "
                    . "inner join persona emp on ven.empleado = emp.id "
                    . "inner join persona pac on ven.paciente = pac.id "
                    . "inner join paciente p on pac.id = p.persona "
                    . "inner join expediente exp on p.id = exp.paciente "
                    . "inner join sucursal suc on ven.sucursal = suc.id "
                    . "inner join paquete_tratamiento pt on paq.id = pt.paquete "
                    . "inner join tratamiento tra on pt.tratamiento = tra.id "
                    . "left outer join descuento des on ven.descuento = des.id "
                    . "where ven.id = '$ventaPaqueteId' and seg.tratamiento = pt.tratamiento";
            
            $rsm->addScalarResult('empleado','empleado');
            $rsm->addScalarResult('paciente','paciente');
            $rsm->addScalarResult('numExp','numExp');
            $rsm->addScalarResult('nomPaquete','nomPaquete');
            $rsm->addScalarResult('costoPaquete','costoPaquete');
            $rsm->addScalarResult('ntrata','ntrata');
            $rsm->addScalarResult('porcentaje','porcentaje');
            $rsm->addScalarResult('venta','venta');
            $rsm->addScalarResult('sesiones','sesiones');
            $rsm->addScalarResult('numSesion','numSesion');
            //$rsm->addScalarResult('abono','abono');
            
            $mensaje = $em->createNativeQuery($sql, $rsm)
                    ->getResult();
            
            $sql2 = "select cast(sum(abo.monto) as decimal(36,2)) abonos "
                    . "from abono abo inner join venta_paquete vp on abo.venta_paquete = vp.id "
                    . "where vp.id = '$ventaPaqueteId'";
            
            $rsm2->addScalarResult('abonos','abonos');
            
            $abonos = $em->createNativeQuery($sql2, $rsm2)
                    ->getResult();
     
            $response = new JsonResponse();
            $response->setData(array(
                                'query'  => $mensaje,
                                'abonos' => $abonos[0]
                            )); 
            
            return $response; 
        } else {    
            return new Response('0');              
        }  
    }
    
    /**
    * Ajax utilizado para buscar las sesiones de tratamiento de un paquete vendido
    *  
    * @Route("/evaluarSesionesPaquete", name="admin_evaluar_sesiones_paquete")
    */
    public function evaluarSesionesPaqueteAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $ventaPaqueteId = $this->get('request')->request->get('id');
            
            $rsm = new ResultSetMapping();
            $em = $this->getDoctrine()->getManager();            
                
            $sql = "select pt.num_sesiones as sesiones, "
                    . "seg.num_sesion as numSesion "
                    . "from venta_paquete ven "
                    . "inner join paquete paq on ven.paquete = paq.id "
                    . "inner join seguimiento_paquete seg on ven.id = seg.id_venta_paquete "
                    . "inner join persona emp on ven.empleado = emp.id "
                    . "inner join persona pac on ven.paciente = pac.id "
                    . "inner join paciente p on pac.id = p.persona "
                    . "inner join expediente exp on p.id = exp.paciente "
                    . "inner join sucursal suc on ven.sucursal = suc.id "
                    . "inner join paquete_tratamiento pt on paq.id = pt.paquete "
                    . "inner join tratamiento tra on pt.tratamiento = tra.id "
                    . "left outer join descuento des on ven.descuento = des.id "
                    . "where ven.id = '$ventaPaqueteId' and seg.tratamiento = pt.tratamiento";
            
            $rsm->addScalarResult('sesiones','sesiones');
            $rsm->addScalarResult('numSesion','numSesion');
            //$rsm->addScalarResult('abono','abono');
            
            $mensaje = $em->createNativeQuery($sql, $rsm)
                    ->getResult();
            
            $response = new JsonResponse();
            $response->setData(array(
                                'query'  => $mensaje,
                            )); 
            
            return $response; 
        } else {    
            return new Response('0');              
        }  
    }
}
