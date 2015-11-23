<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Persona;


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
    public function ingresosPaqueteAction()
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
    public function ingresosEmergenciaAction()
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
    public function barGraficoPaqueteAction(Request $request){
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
    public function barGraficoEmergenciaAction(Request $request){
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
    public function consolidadoPaqueteAction()
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
    public function barGraficoConsolidadoPaqueteAction(Request $request){
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
    public function tratamientoFrecuenteAction()
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
    public function barGraficoTratamientoFrecuenteAction(Request $request){
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
    
    
}

