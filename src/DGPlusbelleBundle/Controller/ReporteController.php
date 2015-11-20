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
     * @Route("/", name="admin_reporte", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function ingresosPaqueteAction()
    {
        
        
        //var_dump($ingresos);
        
        return array(
            //'empleados'=>$empleados,
            //'ingresos' => $ingresos[0],
        );
    }
    
    /**
     * 
     *
     * @Route("/bar_grafico", name="admin_reporte_bar", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function bargraficoAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $mes= date('m');
        $anioUser = $request->get('anioUser');
        //var_dump($anioUser);
        if(!isset($anioUser)){
            $year = date('Y');
        }
        else{
            $year = $anioUser;
        }
        //var_dump($year);
        //$mes= "01";
        //var_dump($year);
        $mesint = intval($mes);
        $mesLabel = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        $entities = $em->getRepository('DGPlusbelleBundle:Usuario')->findAll();
        $empleados = $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('estado'=>true));
        //var_dump($empleado);
        
        /*$dql = "SELECT sum(t.costo)"
                    . " FROM"
                    . " DGPlusbelleBundle:Consulta c"
                    . " JOIN c.tratamiento t"
                    . " JOIN c.empleado emp"
                    . " WHERE c.fechaConsulta LIKE :mes AND emp.estado=true AND emp.id=:idEmpleado";
                $comision = $em->createQuery($dql)
                       ->setParameters(array('idEmpleado'=>$empleado['id'],'mes'=>'_____'.$mes.'___'))
                       ->getResult();
          */      
                
        $ingresos = array();
        //var_dump($mes);
            //echo $i."\n";
            $dql = "SELECT MONTH(vp.fechaVenta) as mes, sum(p.costo) as total FROM DGPlusbelleBundle:VentaPaquete vp "
                . "JOIN vp.paquete p "
                . "WHERE vp.fechaVenta LIKE :year group by vp.fechaVenta";
            $ingresosprev=array();
            
            $ingresosprev = $em->createQuery($dql)
                       ->setParameter('year',$year.'______')
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            
            foreach ($ingresosprev as $key=>$ingreso){
                //var_dump($ingreso);
                $indexMes=$ingresosprev[$key]['mes'];
                    $ingresosprev[$key]['mesnombre']=$mesLabel[$indexMes-1];
                
            }
            //var_dump(count($ingresosprev));
            //var_dump($ingresosprev);
            if( count($ingresosprev)!=0){
                //echo "sdacd";
                array_push($ingresosprev[0], $mesLabel[($mes-1)]);
                array_push($ingresos, $ingresosprev);
                
                return array(
                    'empleados'=>$empleados,
                    'ingresos' => $ingresos[0],
                );
                  
            }  
            else{
                return array(
                    'empleados'=>$empleados,
                    'ingresos' => $ingresos,
                );
            }
            
          
    }
    
    
    
}

