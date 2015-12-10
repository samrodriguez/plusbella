<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\SeguimientoTratamiento;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * SeguimientoTratamiento controller.
 *
 * @Route("/admin/seguimientotratamiento")
 */
class SeguimientoTratamientoController extends Controller
{

    /**
     * Lists all SeguimientoTratamiento entities.
     *
     * @Route("/", name="admin_seguimientotratamiento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a SeguimientoTratamiento entity.
     *
     * @Route("/{id}", name="admin_seguimientotratamiento_show", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SeguimientoTratamiento entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
    
    /**
    * Ajax utilizado para buscar las sesiones de tratamiento vendido
    *  
    * @Route("/buscarInfTratamientoVendido", name="admin_busqueda_tratamiento_seguimiento")
    */
    public function buscarInformacionTratamientoAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $ventaTratamientoId = $this->get('request')->request->get('id');
            
            $rsm = new ResultSetMapping();
            $rsm2 = new ResultSetMapping();
            $em = $this->getDoctrine()->getManager();            
                
            $sql = "select concat_ws(' ', emp.nombres, emp.apellidos) as empleado, "
                    . "concat_ws(' ', pac.nombres, pac.apellidos) as paciente, "
                    . "exp.numero as numExp, "
                    . "pt.costotratamiento as costoTratamiento, "
                    . "des.porcentaje as porcentaje, "
                    . "pt.fecha_venta as venta, "
                    . "pt.num_sesiones as sesiones, "
                    . "seg.num_sesion as numSesion, "
                    . "tra.nombre as ntratamiento "
                    . "from persona_tratamiento pt "
                    . "inner join tratamiento tra on pt.tratamiento = tra.id "
                    . "inner join seguimiento_tratamiento seg on pt.id = seg.id_persona_tratamiento "
                    . "inner join persona emp on pt.empleado = emp.id "
                    . "inner join persona pac on pt.paciente = pac.id "
                    . "inner join paciente p on pac.id = p.persona "
                    . "inner join expediente exp on p.id = exp.paciente "
                    . "inner join sucursal suc on pt.sucursal = suc.id "
                    . "inner join descuento des on pt.descuento = des.id "
                    . "where pt.id = '$ventaTratamientoId'";
            
            $rsm->addScalarResult('empleado','empleado');
            $rsm->addScalarResult('paciente','paciente');
            $rsm->addScalarResult('numExp','numExp');
            $rsm->addScalarResult('costoTratamiento','costoTratamiento');
            $rsm->addScalarResult('porcentaje','porcentaje');
            $rsm->addScalarResult('venta','venta');
            $rsm->addScalarResult('sesiones','sesiones');
            $rsm->addScalarResult('numSesion','numSesion');
           $rsm->addScalarResult('ntratamiento','ntratamiento');
            
            $mensaje = $em->createNativeQuery($sql, $rsm)
                    ->getResult();
            
            $sql2 = "select cast(sum(abo.monto) as decimal(36,2)) abonos "
                    . "from abono abo inner join persona_tratamiento p on abo.persona_tratamiento = p.id "
                    . "where p.id = '$ventaTratamientoId'";
            
            $rsm2->addScalarResult('abonos','abonos');
            
            $abonos = $em->createNativeQuery($sql2, $rsm2)
                    ->getResult();
     
            //var_dump($mensaje);
            
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
}
