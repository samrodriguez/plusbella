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
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->findAll();
        
        return array(
            //'empleados'=>$empleados,
            //'ingresos' => $ingresos[0],
            'sucursales' => $entity,
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
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->findAll();
        
        return array(
            //'empleados'=>$empleados,
            //'ingresos' => $ingresos[0],
            'sucursales' => $entity,
        );
    }
    
    
    /**
     * 
     *
     * @Route("/ingresodiario", name="admin_reporte_diario", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function ingresosdiariosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->findAll();
        
        return array(
            //'empleados'=>$empleados,
            //'ingresos' => $ingresos[0],
            'sucursales' => $entity,
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
        
        
        $originalDate = $anioInicioUser;
        $anioInicioUser = date("Y-m-d", strtotime($originalDate));
        
        $anioFinUser = $request->get('anioFinUser');
        
        $originalDate = $anioFinUser;
        $anioFinUser = date("Y-m-d", strtotime($originalDate));
        
        
        $sucursal = $request->get('sucursal');
        
        
        
        if(!isset($anioInicioUser)){
            $year = date('Y');
        }
        else{
            $year = $anioInicioUser;
        }
                
        $ingresos = array();
        //var_dump($mes);
            //echo $i."\n";
            $dql = "SELECT p.nombre as paquete, sum(p.costo*(1-(d.porcentaje/100))) as total FROM DGPlusbelleBundle:VentaPaquete vp "
                . "JOIN vp.paquete p "
                . "JOIN vp.descuento d "
                . "WHERE vp.fechaVenta BETWEEN :fechainicio AND :fechafin AND vp.sucursal=:sucursal group by 'paquete'";
                   
            $ingresosprev=array();
            
            $ingresosprev = $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser,'sucursal'=>$sucursal))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
        $granTotal = 0;
            if( count($ingresosprev)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                
                //var_dump($ingresosprev);
                
                foreach ($ingresosprev as $row){
                    $granTotal = $granTotal+$row['total'];
                }
                
                array_push($ingresos, $ingresosprev);
                
                
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos[0],
                    'granTotal'=>$granTotal,
                );
                  
            }  
            else{
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos,
                    'granTotal'=>0,
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
        
        
        $originalDate = $anioInicioUser;
        $anioInicioUser = date("Y-m-d", strtotime($originalDate));
        
        $anioFinUser = $request->get('anioFinUser');
        
        $originalDate = $anioFinUser;
        $anioFinUser = date("Y-m-d", strtotime($originalDate));
        
        
        $sucursal = $request->get('sucursal');
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
                . "WHERE c.fechaConsulta BETWEEN :fechainicio AND :fechafin AND tc.nombre = 'Emergencia' AND c.sucursal=:sucursal group by 'mes'";
            $ingresosprev=array();
            
            $ingresosprev = $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser,'sucursal'=>$sucursal))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            
            foreach ($ingresosprev as $key=>$ingreso){
                //var_dump($ingreso);
                $indexMes=$ingresosprev[$key]['mes'];
                    $ingresosprev[$key]['mesnombre']=$mesLabel[$indexMes-1];
                
            }
            $granTotal=0;
            //var_dump($ingresosprev);
            //var_dump($ingresosprev);
            if( count($ingresosprev)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                foreach ($ingresosprev as $row){
                    $granTotal = $granTotal+$row['total'];
                }
                array_push($ingresos, $ingresosprev);
                
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos[0],
                    'granTotal'=>$granTotal,
                );
                  
            }  
            else{
                return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos,
                    'granTotal'=>0,
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
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->findAll();
        
        return array(
            //'empleados'=>$empleados,
            //'ingresos' => $ingresos[0],
            'sucursales' => $entity,
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
        
        
        $originalDate = $anioInicioUser;
        $anioInicioUser = date("Y-m-d", strtotime($originalDate));
        
        $anioFinUser = $request->get('anioFinUser');
        
        $originalDate = $anioFinUser;
        $anioFinUser = date("Y-m-d", strtotime($originalDate));
        
        
        $sucursal = $request->get('sucursal');
        
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
                . "WHERE vp.fechaVenta BETWEEN :fechainicio AND :fechafin AND vp.sucursal=:sucursal group by 'paquete'";
            $ingresosprev=array();
            
            $ingresosprev = $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser,'sucursal'=>$sucursal))
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
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Sucursal')->findAll();
        
        return array(
            //'empleados'=>$empleados,
            //'ingresos' => $ingresos[0],
            'sucursales' => $entity,
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
        
        
        $originalDate = $anioInicioUser;
        $anioInicioUser = date("Y-m-d", strtotime($originalDate));
        
        $anioFinUser = $request->get('anioFinUser');
        
        $originalDate = $anioFinUser;
        $anioFinUser = date("Y-m-d", strtotime($originalDate));
        
        
        $sucursal = $request->get('sucursal');
        
        
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
                . "WHERE c.fechaConsulta BETWEEN :fechainicio AND :fechafin AND tc.nombre = 'Emergencia' AND c.sucursal=:sucursal group by 'tratamiento'";
            $ingresosprev=array();
            
            $ingresosprev = $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser,'sucursal'=>$sucursal))
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
     * @Route("/{fechaInicio}/{fechaFin}/{sucursal}/IngresoPaquete", name="admin_ingresopaquete_pdf", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function ingresoPaquetePdfAction($fechaInicio, $fechaFin,$sucursal)
    {
        $em = $this->getDoctrine()->getManager();
        $rsm = new ResultSetMapping();
        
        
                
        
        $originalDate = $fechaInicio;
        $fechaInicio = date("Y-m-d", strtotime($originalDate));
        
        $originalDate = $fechaFin;
        $fechaFin = date("Y-m-d", strtotime($originalDate));
        
        
        
        
        
        $rsm->addScalarResult('paquete','paquete');
        $rsm->addScalarResult('total','total');
        
        $sql = "SELECT p.nombre as paquete, cast(sum(p.costo) as decimal(36,2)) as total "
                . "from venta_paquete vp INNER JOIN paquete p on vp.paquete = p.id "
                . "WHERE  vp.fecha_venta BETWEEN '$fechaInicio' and  '$fechaFin' AND vp.sucursal=$sucursal group by p.nombre order by sum(p.costo) desc";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $consulta = $query->getResult();
        
        
        $sucursal=$em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursal);
        
        $titulo = "Reporte de ingresos por paquete, sucursal '".$sucursal->getNombre()."'";
        $encabezadoTabla = array('Nombre del Paquete', 'Ingresos ($)');
        $anchoCol = array(47, 30);
        $sangria = 50;
        
        $this->get('fpdf_printer')->generarTotalesPdfPorRango($titulo, $encabezadoTabla, $anchoCol, $consulta, $sangria, $fechaInicio, $fechaFin);
        
    }
    
    /**
     * Generar reporte pdf de Consolidado de paquetes
     *
     * @Route("/{fechaInicio}/{fechaFin}/{sucursal}/ConsolidadoPaquete", name="admin_consolidadopaquete_pdf", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function consolidadoPaquetesPdfAction($fechaInicio, $fechaFin, $sucursal)
    {
        $em = $this->getDoctrine()->getManager();
        $rsm = new ResultSetMapping();
        
        
        
        $originalDate = $fechaInicio;
        $fechaInicio = date("Y-m-d", strtotime($originalDate));
        
        $originalDate = $fechaFin;
        $fechaFin = date("Y-m-d", strtotime($originalDate));
        
        
        $rsm->addScalarResult('paquete','paquete');
        $rsm->addScalarResult('total','total');
        
        $sql = "SELECT p.nombre as paquete, count(vp.id) as total "
                . "from venta_paquete vp INNER JOIN paquete p on vp.paquete = p.id "
                . "WHERE  vp.fecha_venta BETWEEN '$fechaInicio' and  '$fechaFin' AND vp.sucursal=$sucursal group by 1 order by count(vp.id) ";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $consulta = $query->getResult();
        
        $sucursal=$em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursal);
        
        $titulo = "Consolidado de paquetes vendidos, sucursal '".$sucursal->getNombre()."'";
        $encabezadoTabla = array('Nombre del Paquete', 'Ingresos ($)');
        $anchoCol = array(47, 30);
        $sangria = 50;
        
        $this->get('fpdf_printer')->generarPdfPorRango($titulo, $encabezadoTabla, $anchoCol, $consulta, $sangria, $fechaInicio, $fechaFin);
        
    }
    
    /**
     * Generar reporte pdf de Ingreso por consulta de emergencia
     *
     * @Route("/{fechaInicio}/{fechaFin}/{sucursal}/IngresoEmergencia", name="admin_ingresoemergencia_pdf", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function ingresoConsultaEmergenciaPdfAction($fechaInicio, $fechaFin, $sucursal)
    {
        $em = $this->getDoctrine()->getManager();
        $mesLabel = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
       
        
        
        $originalDate = $fechaInicio;
        $fechaInicio = date("Y-m-d", strtotime($originalDate));
        
        $originalDate = $fechaFin;
        $fechaFin = date("Y-m-d", strtotime($originalDate));
        
        
        
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('mes','mes');
        $rsm->addScalarResult('total','total');
        
        $sql = "SELECT month(c.fecha_consulta) as mes, cast(sum(c.costo_consulta) as decimal(36,2)) as total "
                . "from consulta c INNER JOIN tipo_consulta tc on c.tipo_consulta = tc.id "
                . "WHERE  c.fecha_consulta BETWEEN '$fechaInicio' and  '$fechaFin' AND c.sucursal=$sucursal "
                . "AND tc.nombre = 'Emergencia' "
                . "group by month(c.fecha_consulta) "
                . "order by month(c.fecha_consulta)";
        
        $sucursal=$em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursal);
        
        $query = $em->createNativeQuery($sql, $rsm);
        $ingresosemer = $query->getResult();
        $consulta = array();
        
        foreach ($ingresosemer as $key=>$ingreso){
            $indexMes=$ingresosemer[$key]['mes'];
            $consulta[$key]['mesnombre'] = $mesLabel[$indexMes-1]; 
            $consulta[$key]['total'] = $ingresosemer[$key]['total'];
        }
        
        $titulo = "Reporte de ingresos por consulta de emergencia, sucursal '".$sucursal->getNombre()."'";
        $encabezadoTabla = array('Mes', 'Ingresos ($)');
        $anchoCol = array(47, 30);
        $sangria = 50;
        
        $this->get('fpdf_printer')->generarTotalesPdfPorRango($titulo, $encabezadoTabla, $anchoCol, $consulta, $sangria, $fechaInicio, $fechaFin);
        
    }
    
    /**
     * Generar reporte pdf de Ingreso por consulta de emergencia
     *
     * @Route("/{fechaInicio}/{fechaFin}/{sucursal}/TratamientoFrecuente", name="admin_tratamientofrecuenteemergencia_pdf", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function tratamientoFrecuenteEmergenciaPdfAction($fechaInicio, $fechaFin,$sucursal)
    {
        $em = $this->getDoctrine()->getManager();
        //$mesLabel = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
       
        
        $originalDate = $fechaInicio;
        $fechaInicio = date("Y-m-d", strtotime($originalDate));
        
        $originalDate = $fechaFin;
        $fechaFin = date("Y-m-d", strtotime($originalDate));
        
        
        
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('tratamiento','tratamiento');
        $rsm->addScalarResult('total','total');
        
        $sql = "SELECT t.nombre as tratamiento, count(c.tratamiento_id) as total "
                . "from consulta c INNER JOIN tipo_consulta tc on c.tipo_consulta = tc.id "
                . "inner join tratamiento t on c.tratamiento_id = t.id "
                . "WHERE  c.fecha_consulta BETWEEN '$fechaInicio' and  '$fechaFin' "
                . "AND tc.nombre = 'Emergencia' AND c.sucursal = $sucursal "
                . "group by t.nombre "
                . "order by t.nombre";
        
        $dql = "SELECT t.nombre as tratamiento, count(c.tratamiento) as total FROM DGPlusbelleBundle:Consulta c "
                . "JOIN c.tratamiento t "
                . "JOIN c.tipoConsulta tc "
                . "WHERE c.fechaConsulta BETWEEN :fechainicio AND :fechafin AND tc.nombre = 'Emergencia' group by 'tratamiento'";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $consulta = $query->getResult();
        //$consulta = array();
        $sucursal=$em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursal);
        /*foreach ($ingresosemer as $key=>$ingreso){
            $indexMes=$ingresosemer[$key]['mes'];
            $consulta[$key]['mesnombre'] = $mesLabel[$indexMes-1]; 
            $consulta[$key]['total'] = $ingresosemer[$key]['total'];
        }*/
        
        $titulo = "Reporte de ingresos por consulta de emergencia, sucursal '".$sucursal->getNombre()."'";
        $encabezadoTabla = array('Tratamiento', 'Ingresos ($)');
        $anchoCol = array(47, 30);
        $sangria = 50;
        
        $this->get('fpdf_printer')->generarPdfPorRango($titulo, $encabezadoTabla, $anchoCol, $consulta, $sangria, $fechaInicio, $fechaFin);
        
    }
    
    
    
    
    
    /**
     * 
     *
     * @Route("/bar_graficoingresodiario", name="admin_reporte_ingresodiario_grafico", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function bargraficoingresodiarioAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $mes= date('m');
        $anioInicioUser = $request->get('anioInicioUser');
        
        $originalDate = $anioInicioUser;
        $anioInicioUser = date("Y-m-d", strtotime($originalDate));
        
        $anioFinUser = $request->get('anioFinUser');
        
        $originalDate = $anioFinUser;
        $anioFinUser = date("Y-m-d", strtotime($originalDate));
        
        
        $sucursal = $request->get('sucursal');
        
                
        $ingresos = array();
        //var_dump($mes);
            //echo $i."\n";
            $dql = "SELECT sum(c.costoConsulta) as total FROM DGPlusbelleBundle:Consulta c "
                . "WHERE c.fechaConsulta BETWEEN :fechainicio AND :fechafin AND c.sucursal=:sucursal";
                   
            
        $granTotal=0;    
            $ingresosConsulta = $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser,'sucursal'=>$sucursal))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            //echo "consultas";
            //var_dump($ingresosConsulta);
            array_push($ingresos, "Consultas");
            if( count($ingresosConsulta)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                if($ingresosConsulta[0]['total']==null){
                    $ingresosConsulta[0]['total']=0;
                }
                
                $granTotal = $granTotal+$ingresosConsulta[0]['total'];
                array_push($ingresos, $ingresosConsulta[0]['total']);
                
                
            }
            else{
                array_push($ingresos, 0);
            }
            
            $dql = "SELECT sum(p.costo*(1-(d.porcentaje/100))) as total FROM DGPlusbelleBundle:VentaPaquete vp "
                    . "INNER JOIN vp.paquete p "
                    . "JOIN vp.descuento d "
                    . "WHERE vp.cuotas=1 AND vp.fechaVenta BETWEEN :fechainicio AND :fechafin AND vp.sucursal=:sucursal ";
                   
            
            
            $ingresosVentaPaquete= $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser,'sucursal'=>$sucursal))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            //echo "ventaspaquetes";
            
            array_push($ingresos, "Ventas paquetes");
            if( count($ingresosVentaPaquete)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                
                if($ingresosVentaPaquete[0]['total']==null){
                    $ingresosVentaPaquete[0]['total']=0;
                }
                
                $granTotal = $granTotal+$ingresosVentaPaquete[0]['total'];
                array_push($ingresos, $ingresosVentaPaquete[0]['total']);
                
            }  
            else{
                array_push($ingresos, 0);
            }
            
            
            $dql = "SELECT sum(a.monto) as total FROM DGPlusbelleBundle:Abono a "
                    . "WHERE a.fechaAbono BETWEEN :fechainicio AND :fechafin AND a.sucursal=:sucursal";
                   
            
            
            $ingresosAbono= $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser,'sucursal'=>$sucursal))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
          
            //var_dump($ingresosAbono);
            array_push($ingresos, "Abonos");
            if( count($ingresosAbono)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                //array_push($ingresos, $ingresosAbono);
                if($ingresosAbono[0]['total']==null){
                    $ingresosAbono[0]['total']=0;
                }
                
                $granTotal = $granTotal+$ingresosAbono[0]['total'];
                array_push($ingresos, $ingresosAbono[0]['total']);
                
            }  
            else{
                array_push($ingresos, 0);
            }
            
            $dql = "SELECT sum(a.costoConsulta*(1-(d.porcentaje/100))) as total FROM DGPlusbelleBundle:PersonaTratamiento a "
                    . "JOIN a.descuento d "
                    . "WHERE a.fechaVenta BETWEEN :fechainicio AND :fechafin AND a.sucursal=:sucursal";
                   
            
            
            $ingresosAbono= $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$anioInicioUser,'fechafin'=>$anioFinUser,'sucursal'=>$sucursal))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
          
            //var_dump($ingresosAbono);
            array_push($ingresos, "Tratamiento");
            if( count($ingresosAbono)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                //array_push($ingresos, $ingresosAbono);
                if($ingresosAbono[0]['total']==null){
                    $ingresosAbono[0]['total']=0;
                }
                
                $granTotal = $granTotal+$ingresosAbono[0]['total'];
                array_push($ingresos, $ingresosAbono[0]['total']);
                
            }  
            else{
                array_push($ingresos, 0);
            }
            
            
            
            //var_dump($consultas);
            
            return array(
                    //'empleados'=>$empleados,
                    'ingresos' => $ingresos,
                    'granTotal'=>$granTotal,
                );
    }
    
    
    
    /**
     * Generar reporte pdf de Ingresos diarios por sucursal
     *
     * @Route("/{fechaInicio}/{fechaFin}/{sucursal}/Ingresodiarios", name="admin_ingresodiario_pdf", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function ingresoDiarioPdfAction($fechaInicio, $fechaFin,$sucursal)
    {
        $em = $this->getDoctrine()->getManager();
        $rsm = new ResultSetMapping();
        
                
        
        $originalDate = $fechaInicio;
        $fechaInicio = date("Y-m-d", strtotime($originalDate));
        
        $originalDate = $fechaFin;
        $fechaFin = date("Y-m-d", strtotime($originalDate));
        
        
        
        $rsm->addScalarResult('paquete','paquete');
        $rsm->addScalarResult('total','total');
        
        
        $ingresos = array();
        //var_dump($mes);
            //echo $i."\n";
            $dql = "SELECT sum(c.costoConsulta) as total FROM DGPlusbelleBundle:Consulta c "
                . "WHERE c.fechaConsulta BETWEEN :fechainicio AND :fechafin AND c.sucursal=:sucursal";
                   
            
        $granTotal=0;    
            $ingresosConsulta = $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$fechaInicio,'fechafin'=>$fechaFin,'sucursal'=>$sucursal))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            //echo "consultas";
            //var_dump($ingresosConsulta);
            array_push($ingresos, "Consultas");
            if( count($ingresosConsulta)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                if($ingresosConsulta[0]['total']==null){
                    $ingresosConsulta[0]['total']=0;
                }//number_format((float)$number, 2, '.', '');
                $consultas=["Consultas",  number_format($ingresosConsulta[0]['total'],2,'.','')];
                $granTotal = $granTotal+$ingresosConsulta[0]['total'];
                array_push($ingresos, $ingresosConsulta[0]['total']);
                
                
            }
            else{
                array_push($ingresos, 0);
            }
            
            $dql = "SELECT sum(p.costo) as total FROM DGPlusbelleBundle:VentaPaquete vp "
                    . "INNER JOIN vp.paquete p "
                    . "WHERE vp.cuotas=1 AND vp.fechaVenta BETWEEN :fechainicio AND :fechafin AND vp.sucursal=:sucursal ";
                   
            
            
            $ingresosVentaPaquete= $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$fechaInicio,'fechafin'=>$fechaFin,'sucursal'=>$sucursal))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            //echo "ventaspaquetes";
            
            array_push($ingresos, "Ventas paquetes");
            if( count($ingresosVentaPaquete)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                
                if($ingresosVentaPaquete[0]['total']==null){
                    $ingresosVentaPaquete[0]['total']=0;
                }
                $ventasPaquetes=["Ventas paquetes",  number_format($ingresosVentaPaquete[0]['total'],2,'.','')];
                $granTotal = $granTotal+$ingresosVentaPaquete[0]['total'];
                array_push($ingresos, $ingresosVentaPaquete[0]['total']);
                
            }  
            else{
                array_push($ingresos, 0);
            }
            
            
            $dql = "SELECT sum(a.monto) as total FROM DGPlusbelleBundle:Abono a "
                    . "WHERE a.fechaAbono BETWEEN :fechainicio AND :fechafin AND a.sucursal=:sucursal";
                   
            
            
            $ingresosAbono= $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$fechaInicio,'fechafin'=>$fechaFin,'sucursal'=>$sucursal))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
          
            //var_dump($ingresosAbono);
            array_push($ingresos, "Abonos");
            if( count($ingresosAbono)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                //array_push($ingresos, $ingresosAbono);
                if($ingresosAbono[0]['total']==null){
                    $ingresosAbono[0]['total']=0;
                }
                $abonos=["Abonos",  number_format($ingresosAbono[0]['total'],2,'.','')];
                $granTotal = $granTotal+$ingresosAbono[0]['total'];
                array_push($ingresos, $ingresosAbono[0]['total']);
                
            }  
            else{
                array_push($ingresos, 0);
            }
            
            $dql = "SELECT sum(a.costoConsulta) as total FROM DGPlusbelleBundle:PersonaTratamiento a "
                    . "WHERE a.fechaVenta BETWEEN :fechainicio AND :fechafin AND a.sucursal=:sucursal";
                   
            
            
            $ingresosAbono= $em->createQuery($dql)
                       ->setParameters(array('fechainicio'=>$fechaInicio,'fechafin'=>$fechaFin,'sucursal'=>$sucursal))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
          
            //var_dump($ingresosAbono);
            array_push($ingresos, "Tratamiento");
            if( count($ingresosAbono)!=0){
                //echo "sdacd";
                //array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                //array_push($ingresos, $ingresosAbono);
                if($ingresosAbono[0]['total']==null){
                    $ingresosAbono[0]['total']=0;
                }
                $tratamiento=["Tratamiento",  number_format($ingresosAbono[0]['total'],2,'.','')];
                $granTotal = $granTotal+$ingresosAbono[0]['total'];
                array_push($ingresos, $ingresosAbono[0]['total']);
                
            }
            else{
                array_push($ingresos, 0);
            }
        
        
        
        
        
        
        
        
        $consulta2=array();
        
        
        
        array_push($consulta2, $consultas);
        array_push($consulta2, $ventasPaquetes);
        array_push($consulta2, $abonos);
        array_push($consulta2, $tratamiento);
        
        
        
        
        
        
        
        
        
        
        
        
        
        /*$query = $em->createNativeQuery($sql, $rsm);
        $consulta = $query->getResult();*/
        
        
        $sucursal=$em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursal);
        
        $titulo = "Reporte de ingresos diarios, sucursal '".$sucursal->getNombre()."'";
        $encabezadoTabla = array('Actividad', 'Ingresos ($)');
        $anchoCol = array(47, 30);
        $sangria = 50;
        
        $this->get('fpdf_printer')->generarTotalesPdfPorRango($titulo, $encabezadoTabla, $anchoCol, $consulta2, $sangria, $fechaInicio, $fechaFin);
        
    }
    
    
    
}

