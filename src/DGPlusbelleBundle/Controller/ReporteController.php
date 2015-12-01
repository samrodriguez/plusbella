<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Persona;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Reporte controller.
 *
 * @Route("/admin/reporte")
 */
class ReporteController extends Controller
{

    /**
     * 
     *
     * @Route("/ingresopaquete", name="admin_reporte_paquete", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function ingresospaqueteAction()
    {
        return array(
            //'empleados'=>$empleados,
            //'ingresos' => $ingresos[0],
        );
    }
    
    
    
    /**
     * 
     *
     * @Route("/ingresoemergencia", name="admin_reporte_emergencia", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function ingresosemergenciaAction()
    {
        return array(
            //'empleados'=>$empleados,
            //'ingresos' => $ingresos[0],
        );
    }
    
    /**
     * 
     *
     * @Route("/bar_graficoingresopaquete", name="admin_reporte_paquete_grafico", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function bargraficopaqueteAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $mes= date('m');
        $anioInicioUser = $request->get('anioInicioUser');
        $anioFinUser = $request->get('anioFinUser');
        if(!isset($anioInicioUser)){
            $year = date('Y');
        }
        else{
            $year = $anioInicioUser;
        }
                
        $ingresos = array();
        //var_dump($mes);
            //echo $i."\n";
            $dql = "SELECT p.nombre as paquete, sum(p.costo) as total FROM DGPlusbelleBundle:VentaPaquete vp "
                . "JOIN vp.paquete p "
                . "WHERE vp.fechaVenta BETWEEN :fechainicio AND :fechafin group by 'paquete'";
                   
            $ingresosprev=array();
            
            $ingresosprev = $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            
            if( count($ingresosprev)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                array_push($ingresos, $ingresosprev);
                
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos[0],
                );
                  
            }  
            else{
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos,
                );
            }
            
          
    }
    
    
    
    /**
     * 
     *
     * @Route("/bar_graficoingresoemergencia", name="admin_reporte_emergencia_grafico", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function bargraficoemergenciaAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $mes= date('m');
        $anioInicioUser = $request->get('anioInicioUser');
        $anioFinUser = $request->get('anioFinUser');
        //var_dump($anioUser);
        if(!isset($anioUser)){
            $year = date('Y');
        }
        else{
            $year = $anioUser;
        }
        
        $mesint = intval($mes);
        $mesLabel = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                
        $ingresos = array();
        //var_dump($mes);
            //echo $i."\n";
            $dql = "SELECT MONTH(c.fechaConsulta) as mes, sum(c.costoConsulta) as total FROM DGPlusbelleBundle:Consulta c "
                . "JOIN c.tipoConsulta tc "
                . "WHERE c.fechaConsulta BETWEEN :fechainicio AND :fechafin AND tc.nombre = 'Emergencia' group by 'mes'";
            $ingresosprev=array();
            
            $ingresosprev = $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            
            foreach ($ingresosprev as $key=>$ingreso){
                //var_dump($ingreso);
                $indexMes=$ingresosprev[$key]['mes'];
                    $ingresosprev[$key]['mesnombre']=$mesLabel[$indexMes-1];
                
            }
            //var_dump($ingresosprev);
            //var_dump($ingresosprev);
            if( count($ingresosprev)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                array_push($ingresos, $ingresosprev);
                
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos[0],
                );
                  
            }  
            else{
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos,
                );
            }
            
          
    }
    
    
    
    
    //////////////////////////////CONSOLIDADO DE PAQUETES///////////////////////
    
    /**
     * 
     *
     * @Route("/consolidadopaquete", name="admin_reporte_consolidado_paquete", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function consolidadopaqueteAction()
    {
        return array(
            //'empleados'=>$empleados,
            //'ingresos' => $ingresos[0],
        );
    }
    
    
    /**
     * 
     *
     * @Route("/bar_graficoconsolidadopaquete", name="admin_reporte_consolidado_grafico", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function bargraficoconsolidadopaqueteAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $mes= date('m');
        $anioInicioUser = $request->get('anioInicioUser');
        $anioFinUser = $request->get('anioFinUser');
        //var_dump($anioUser);
        if(!isset($anioUser)){
            $year = date('Y');
        }
        else{
            $year = $anioUser;
        }
        
        $mesint = intval($mes);
        $mesLabel = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                
        $ingresos = array();
        //var_dump($mes);
            //echo $i."\n";
            $dql = "SELECT p.nombre as paquete, count(vp.id) as total FROM DGPlusbelleBundle:VentaPaquete vp "
                . "JOIN vp.paquete p "
                . "WHERE vp.fechaVenta BETWEEN :fechainicio AND :fechafin group by 'paquete'";
            $ingresosprev=array();
            
            $ingresosprev = $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            /*
            foreach ($ingresosprev as $key=>$ingreso){
                //var_dump($ingreso);
                $indexMes=$ingresosprev[$key]['mes'];
                    $ingresosprev[$key]['mesnombre']=$mesLabel[$indexMes-1];
                
            }*/
            //var_dump($ingresosprev);
            //var_dump($ingresosprev);
            if( count($ingresosprev)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                array_push($ingresos, $ingresosprev);
                
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos[0],
                );
                  
            }  
            else{
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos,
                );
            }
            
          
    }
    
    /**
     * 
     *
     * @Route("/tratamientofrecuente", name="admin_reporte_tratamiento_frecuente", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function tratamientofrecuenteAction()
    {
        return array(
            //'empleados'=>$empleados,
            //'ingresos' => $ingresos[0],
        );
    }
    
    ///////Tratamiento mas frecuente en consulta de emergencia
    
    /**
     * 
     *
     * @Route("/bar_graficotratamientofrecuente", name="admin_reporte_tratamiento_frecuente_grafico", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function bargraficotratamientofrecuenteAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $mes= date('m');
        $anioInicioUser = $request->get('anioInicioUser');
        $anioFinUser = $request->get('anioFinUser');
        //var_dump($anioUser);
        if(!isset($anioUser)){
            $year = date('Y');
        }
        else{
            $year = $anioUser;
        }
        
        //$mesint = intval($mes);
        //$mesLabel = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                
        $ingresos = array();
        //var_dump($mes);
            //echo $i."\n";
            $dql = "SELECT t.nombre as tratamiento, count(c.tratamiento) as total FROM DGPlusbelleBundle:Consulta c "
                . "JOIN c.tratamiento t "
                . "JOIN c.tipoConsulta tc "
                . "WHERE c.fechaConsulta BETWEEN :fechainicio AND :fechafin AND tc.nombre = 'Emergencia' group by 'tratamiento'";
            $ingresosprev=array();
            
            $ingresosprev = $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            /*
            foreach ($ingresosprev as $key=>$ingreso){
                //var_dump($ingreso);
                $indexMes=$ingresosprev[$key]['mes'];
                    $ingresosprev[$key]['mesnombre']=$mesLabel[$indexMes-1];
                
            }*/
            //var_dump($ingresosprev);
            //var_dump($ingresosprev);
            if( count($ingresosprev)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                array_push($ingresos, $ingresosprev);
                
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos[0],
                );
                  
            }  
            else{
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos,
                );
            }
            
          
    }
    
    /**
     * Generar reporte pdf de Detalle Plantilla consulta
     *
     * @Route("/{id}/ReporteConsulta", name="admin_reporteplantilla_pdf", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function DetallePlantillaPdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $dql = "SELECT hc, con, pac, exp, per1, per2, emp, det, pla "
                    . "FROM DGPlusbelleBundle:HistorialConsulta hc "
                    . "JOIN hc.consulta con "
                    . "JOIN con.paciente pac "
                    . "JOIN pac.expediente exp "
                    . "JOIN pac.persona per1 "
                    . "JOIN con.empleado emp "
                    . "JOIN emp.persona per2 "
                    . "JOIN hc.detallePlantilla det "
                    . "JOIN det.plantilla pla "
                    . "WHERE con.id =  :idconsulta";
            
        $consulta = $em->createQuery($dql)
                    ->setParameter('idconsulta', $id)
                    ->getResult();
        
        //$titulo = 'Reporte de Videoendoscopia';
        
        
        $medico = array(
                    "nombre" => "Dr. Juan Carlos Pacheco",
                    "cargo" => "Cirujano Endoscopista",
                    "codigo" => "JVPM 7370",
                );
        
        $logo = '../web/Resources/img/dgplusbelle/images/';
       
        $this->get('fpdf_printer')->generarPlantilla($logo, $consulta, $medico);
    }
  
    /**
     * Generar reporte pdf de Ingresos por Paquete
     *
     * @Route("/{fechaInicio}/{fechaFin}/IngresoPaquete", name="admin_ingresopaquete_pdf", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function ingresoPaquetePdfAction($fechaInicio, $fechaFin)
    {
        $em = $this->getDoctrine()->getManager();
        $rsm = new ResultSetMapping();
        
        $rsm->addScalarResult('paquete','paquete');
        $rsm->addScalarResult('total','total');
        
        $sql = "SELECT p.nombre as paquete, cast(sum(p.costo) as decimal(36,2)) as total "
                . "from venta_paquete vp INNER JOIN paquete p on vp.paquete = p.id "
                . "WHERE  vp.fecha_venta BETWEEN '$fechaInicio' and  '$fechaFin' group by p.nombre order by sum(p.costo) desc";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $consulta = $query->getResult();
        
        $titulo = "Reporte de ingresos por paquete";
        $encabezadoTabla = array('Nombre del Paquete', 'Ingresos ($)');
        $anchoCol = array(47, 30);
        $sangria = 50;
        
        $this->get('fpdf_printer')->generarPdfPorRango($titulo, $encabezadoTabla, $anchoCol, $consulta, $sangria, $fechaInicio, $fechaFin);
        
    }
    
    /**
     * Generar reporte pdf de Consolidado de paquetes
     *
     * @Route("/{fechaInicio}/{fechaFin}/ConsolidadoPaquete", name="admin_consolidadopaquete_pdf", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function consolidadoPaquetesPdfAction($fechaInicio, $fechaFin)
    {
        $em = $this->getDoctrine()->getManager();
        $rsm = new ResultSetMapping();
        
        $rsm->addScalarResult('paquete','paquete');
        $rsm->addScalarResult('total','total');
        
        $sql = "SELECT p.nombre as paquete, count(vp.id) as total "
                . "from venta_paquete vp INNER JOIN paquete p on vp.paquete = p.id "
                . "WHERE  vp.fecha_venta BETWEEN '$fechaInicio' and  '$fechaFin' order by count(vp.id)";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $consulta = $query->getResult();
        
        $titulo = "Consolidado de paquetes vendidos";
        $encabezadoTabla = array('Nombre del Paquete', 'Ingresos ($)');
        $anchoCol = array(47, 30);
        $sangria = 50;
        
        $this->get('fpdf_printer')->generarPdfPorRango($titulo, $encabezadoTabla, $anchoCol, $consulta, $sangria, $fechaInicio, $fechaFin);
        
    }
    
    /**
     * Generar reporte pdf de Ingreso por consulta de emergencia
     *
     * @Route("/{fechaInicio}/{fechaFin}/IngresoEmergencia", name="admin_ingresoemergencia_pdf", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function ingresoConsultaEmergenciaPdfAction($fechaInicio, $fechaFin)
    {
        $em = $this->getDoctrine()->getManager();
        $mesLabel = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
       
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('mes','mes');
        $rsm->addScalarResult('total','total');
        
        $sql = "SELECT month(c.fecha_consulta) as mes, cast(sum(c.costo_consulta) as decimal(36,2)) as total "
                . "from consulta c INNER JOIN tipo_consulta tc on c.tipo_consulta = tc.id "
                . "WHERE  c.fecha_consulta BETWEEN '$fechaInicio' and  '$fechaFin' "
                . "AND tc.nombre = 'Emergencia' "
                . "group by month(c.fecha_consulta) "
                . "order by month(c.fecha_consulta)";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $ingresosemer = $query->getResult();
        $consulta = array();
        
        foreach ($ingresosemer as $key=>$ingreso){
            $indexMes=$ingresosemer[$key]['mes'];
            $consulta[$key]['mesnombre'] = $mesLabel[$indexMes-1]; 
            $consulta[$key]['total'] = $ingresosemer[$key]['total'];
        }
        
        $titulo = "Reporte de ingresos por consulta de emergencia";
        $encabezadoTabla = array('Mes', 'Ingresos ($)');
        $anchoCol = array(47, 30);
        $sangria = 50;
        
        $this->get('fpdf_printer')->generarPdfPorRango($titulo, $encabezadoTabla, $anchoCol, $consulta, $sangria, $fechaInicio, $fechaFin);
        
    }
    
    /**
     * Generar reporte pdf de Ingreso por consulta de emergencia
     *
     * @Route("/{fechaInicio}/{fechaFin}/TratamientoFrecuente", name="admin_tratamientofrecuenteemergencia_pdf", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function tratamientoFrecuenteEmergenciaPdfAction($fechaInicio, $fechaFin)
    {
        $em = $this->getDoctrine()->getManager();
        //$mesLabel = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
       
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('tratamiento','tratamiento');
        $rsm->addScalarResult('total','total');
        
        $sql = "SELECT t.nombre as tratamiento, count(c.tratamiento_id) as total "
                . "from consulta c INNER JOIN tipo_consulta tc on c.tipo_consulta = tc.id "
                . "inner join tratamiento t on c.tratamiento_id = t.id "
                . "WHERE  c.fecha_consulta BETWEEN '$fechaInicio' and  '$fechaFin' "
                . "AND tc.nombre = 'Emergencia' "
                . "group by t.nombre "
                . "order by t.nombre";
        
        $dql = "SELECT t.nombre as tratamiento, count(c.tratamiento) as total FROM DGPlusbelleBundle:Consulta c "
                . "JOIN c.tratamiento t "
                . "JOIN c.tipoConsulta tc "
                . "WHERE c.fechaConsulta BETWEEN :fechainicio AND :fechafin AND tc.nombre = 'Emergencia' group by 'tratamiento'";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $consulta = $query->getResult();
        //$consulta = array();
        
        /*foreach ($ingresosemer as $key=>$ingreso){
            $indexMes=$ingresosemer[$key]['mes'];
            $consulta[$key]['mesnombre'] = $mesLabel[$indexMes-1]; 
            $consulta[$key]['total'] = $ingresosemer[$key]['total'];
        }*/
        
        $titulo = "Reporte de ingresos por consulta de emergencia";
        $encabezadoTabla = array('Mes', 'Ingresos ($)');
        $anchoCol = array(47, 30);
        $sangria = 50;
        
        $this->get('fpdf_printer')->generarPdfPorRango($titulo, $encabezadoTabla, $anchoCol, $consulta, $sangria, $fechaInicio, $fechaFin);
        
    }
}

