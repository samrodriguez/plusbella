<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * venta controller.
 *
 * @Route("/admin/venta")
 */
class VentaController  extends Controller
{
    /**
     * Nueva venta de paquete o tratamiento
     *
     * @Route("/", name="admin_historial_consulta", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function nuevaVentaAction(){
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del id
        $request = $this->getRequest();
        
        $idPacient= $request->get('id');  
        $idPaciente=  substr($idPacient, 1);
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        
        $edad="";
        if(count($paciente)!=0){
            $fecha = $paciente->getFechaNacimiento();
            if($fecha!=null){
                $fecha = $paciente->getFechaNacimiento()->format("Y-m-d");
                
                //Calculo de la edad
                list($Y,$m,$d) = explode("-",$fecha);
                $edad = date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y;       
                $edad = $edad. " años";
            }
            else{
                $edad = "No se ha ingresado fecha de nacimiento";
            }
        }
        else{
            $consultas=null;
        }
        $expnum="";
        if(is_null($paciente->getExpediente()[0])){
            $expnum = $this->generarExpediente($paciente);
        }
        else{
            $expnum = $paciente->getExpediente()[0]->getNumero();
        }
        
        $CompraPaciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        $paciente = $CompraPaciente;
        
        $regnoeditpaquete = array();
        $regnoedittratamiento = array();

        $ventaPaquetes = $em->getRepository('DGPlusbelleBundle:Paquete')->findBy(array('estado' => true));
        $ventaTratamientos = $em->getRepository('DGPlusbelleBundle:Tratamiento')->findBy(array('estado' => true));
        $sucursales = $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array('estado' => true));
        $empleadosVenta = $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('estado' => true));
        $descuentos = $em->getRepository('DGPlusbelleBundle:Descuento')->findBy(array('estado' => true));
        
        return array(
            'edad' => $edad,
            'paciente' => $paciente,
            'expediente'=>$expnum,
            'paquetesnoedit'=>$regnoeditpaquete,
            'tratamientosnoedit'=>$regnoedittratamiento,
            'ventaPaquetes' => $ventaPaquetes,
            'ventaTratamientos' => $ventaTratamientos,
            'sucursales' => $sucursales,
            'empleadosVenta' => $empleadosVenta,
            'descuentos' => $descuentos,
            'idPaciente'=>$idPacient
            );
    }
    
    /**
     * Editar venta de paquete 
     *
     * @Route("/paquete/", name="admin_editarventa_paquete", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function editarVentaPaqueteAction(){
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del id
        $request = $this->getRequest();
        
        $idPacient= $request->get('id');  
        $idPaciente=  substr($idPacient, 1);
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        
        $edad="";
        if(count($paciente)!=0){
            $fecha = $paciente->getFechaNacimiento();
            if($fecha!=null){
                $fecha = $paciente->getFechaNacimiento()->format("Y-m-d");
                
                //Calculo de la edad
                list($Y,$m,$d) = explode("-",$fecha);
                $edad = date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y;       
                $edad = $edad. " años";
            }
            else{
                $edad = "No se ha ingresado fecha de nacimiento";
            }
        }
        else{
            $consultas=null;
        }
        $expnum="";
        if(is_null($paciente->getExpediente()[0])){
            $expnum = $this->generarExpediente($paciente);
        }
        else{
            $expnum = $paciente->getExpediente()[0]->getNumero();
        }
        
        $CompraPaciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        $paciente = $CompraPaciente;
        
        $regnoeditpaquete = array();
        $regnoedittratamiento = array();

        $ventaPaquetes = $em->getRepository('DGPlusbelleBundle:Paquete')->findBy(array('estado' => true));
        $ventaTratamientos = $em->getRepository('DGPlusbelleBundle:Tratamiento')->findBy(array('estado' => true));
        $sucursales = $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array('estado' => true));
        $empleadosVenta = $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('estado' => true));
        $descuentos = $em->getRepository('DGPlusbelleBundle:Descuento')->findBy(array('estado' => true));
        
        return array(
            'edad' => $edad,
            'paciente' => $paciente,
            'expediente'=>$expnum,
            'paquetesnoedit'=>$regnoeditpaquete,
            'tratamientosnoedit'=>$regnoedittratamiento,
            'ventaPaquetes' => $ventaPaquetes,
            'ventaTratamientos' => $ventaTratamientos,
            'sucursales' => $sucursales,
            'empleadosVenta' => $empleadosVenta,
            'descuentos' => $descuentos,
            'idPaciente'=>$idPacient
            );
    }
    
    /**
     * Editar venta de tratamiento
     *
     * @Route("/tratamiento/", name="admin_editarventa_tratamiento", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function editarVentaTratamientoAction(){
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del id
        $request = $this->getRequest();
        
        $idPacient= $request->get('id');  
        $idPaciente=  substr($idPacient, 1);
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        
        $edad="";
        if(count($paciente)!=0){
            $fecha = $paciente->getFechaNacimiento();
            if($fecha!=null){
                $fecha = $paciente->getFechaNacimiento()->format("Y-m-d");
                
                //Calculo de la edad
                list($Y,$m,$d) = explode("-",$fecha);
                $edad = date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y;       
                $edad = $edad. " años";
            }
            else{
                $edad = "No se ha ingresado fecha de nacimiento";
            }
        }
        else{
            $consultas=null;
        }
        $expnum="";
        if(is_null($paciente->getExpediente()[0])){
            $expnum = $this->generarExpediente($paciente);
        }
        else{
            $expnum = $paciente->getExpediente()[0]->getNumero();
        }
        
        $CompraPaciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        $paciente = $CompraPaciente;
        
        $regnoeditpaquete = array();
        $regnoedittratamiento = array();

        $ventaPaquetes = $em->getRepository('DGPlusbelleBundle:Paquete')->findBy(array('estado' => true));
        $ventaTratamientos = $em->getRepository('DGPlusbelleBundle:Tratamiento')->findBy(array('estado' => true));
        $sucursales = $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array('estado' => true));
        $empleadosVenta = $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('estado' => true));
        $descuentos = $em->getRepository('DGPlusbelleBundle:Descuento')->findBy(array('estado' => true));
        
        return array(
            'edad' => $edad,
            'paciente' => $paciente,
            'expediente'=>$expnum,
            'paquetesnoedit'=>$regnoeditpaquete,
            'tratamientosnoedit'=>$regnoedittratamiento,
            'ventaPaquetes' => $ventaPaquetes,
            'ventaTratamientos' => $ventaTratamientos,
            'sucursales' => $sucursales,
            'empleadosVenta' => $empleadosVenta,
            'descuentos' => $descuentos,
            'idPaciente'=>$idPacient
            );
    }
    
    /**
    * Ajax utilizado para registrar una nueva sesion de tratamiento
    *  
    * @Route("/registro-sesion-tratamiento/set", name="admin_sesiontratamiento_nuevo")
    */
    public function registroSesionTratamientoPaqueteAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $em = $this->getDoctrine()->getManager();
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            
            $id = $this->get('request')->request->get('id');
            $sucursalId = $this->get('request')->request->get('sucursal');
            $empleadoId = $this->get('request')->request->get('empleado');
            $tratamientoId = $this->get('request')->request->get('tratamiento');
            
            $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursalId);
            $empleado = $em->getRepository('DGPlusbelleBundle:Empleado')->find($empleadoId);
            $tratamiento = $em->getRepository('DGPlusbelleBundle:Tratamiento')->find($tratamientoId);
            $entity = new \DGPlusbelleBundle\Entity\SesionTratamiento();

            $entity->setFechaSesion(new \DateTime('now'));
            $entity->setEmpleado($empleado);
            $entity->setSucursal($sucursal);
            $entity->setTratamiento($tratamiento);
            
            $ventaPaquete = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);

            $entity->setVentaPaquete($ventaPaquete);
            $entity->setRegistraReceta("0");
            $em->persist($entity);
            $em->flush();

            $id2=$entity->getId();
            $entity2 =  $em->getRepository('DGPlusbelleBundle:SesionTratamiento')->find($id2);

            $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findOneBy(array('idVentaPaquete' => $id, 'tratamiento' => $entity->getTratamiento()->getId()));
            $tratamientos = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findBy(array('ventaPaquete' => $ventaPaquete->getId()));
            
            $aux = 0;
            $total = count($tratamientos);
            foreach ($tratamientos as $trat){
                 if($seguimiento->getNumSesion() + 1 >= $trat->getNumSesiones()){
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


            $seguimiento->setNumSesion($seguimiento->getNumSesion() + 1);
            $em->merge($seguimiento);
            $em->flush();
            
            $rsm = new ResultSetMapping();
            $em = $this->getDoctrine()->getManager();            
                
            $sql = "select ven.id as id,"
                    . "paq.nombre as nomPaquete, "
                    . "tra.nombre as ntrata, "
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
                    . "where ven.id = '$id' and seg.tratamiento = pt.tratamiento";
            
            $rsm->addScalarResult('id','id');
            $rsm->addScalarResult('nomPaquete','nomPaquete');
            $rsm->addScalarResult('ntrata','ntrata');
            $rsm->addScalarResult('sesiones','sesiones');
            $rsm->addScalarResult('numSesion','numSesion');
            
            $mensaje = $em->createNativeQuery($sql, $rsm)
                    ->getResult();
            
            $this->get('bitacora')->escribirbitacora("Se registro una nueva sesion de tratamiento", $usuario->getId());
            
            $response = new JsonResponse();
            $response->setData(array(
                                'exito'       => '1',
                                'ventaPaquete' => $mensaje,
                               ));  
            
            return $response; 
        } else {    
            return new Response('0');              
        } 
    }
    
    /**
    * Ajax utilizado para registrar una nueva sesion de tratamiento
    *  
    * @Route("/registro-sesiontratamiento/set", name="admin_sesionventatratamiento_nuevo")
    */
    public function registroSesionTratamientoAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $em = $this->getDoctrine()->getManager();
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            
            $id = $this->get('request')->request->get('id');
            $sucursalId = $this->get('request')->request->get('sucursal');
            $empleadoId = $this->get('request')->request->get('empleado');
            
            $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursalId);
            $empleado = $em->getRepository('DGPlusbelleBundle:Empleado')->find($empleadoId);
            $personaTratamiento = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($id);
            
            $entity = new \DGPlusbelleBundle\Entity\SesionVentaTratamiento();
            $seguimiento1 = new \DGPlusbelleBundle\Entity\ImagenTratamiento();
            
            $entity->setEmpleado($empleado);
            $entity->setSucursal($sucursal);
            $entity->setPersonaTratamiento($personaTratamiento);
            $entity->setFechaSesion(new \DateTime('now'));
            
            $em->persist($entity);
            $em->flush();

            $id2 = $entity->getId();

            $entity2 =  $em->getRepository('DGPlusbelleBundle:SesionVentaTratamiento')->find($id2);
            $seguimiento1->setSesionVentaTratamiento($entity2);

            $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findOneBy(array('idPersonaTratamiento' => $id));
            $seguimiento->setNumSesion($seguimiento->getNumSesion() + 1);
            $em->merge($seguimiento);
            $em->flush();

            //$paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->findOneBy(array('persona' => $entity->getPersonaTratamiento ()->getPaciente()->getId()));
            
            $sesionTratamiento = array(
                                        'id' => $personaTratamiento->getId(), 
                                        'sesiones' => $personaTratamiento->getNumSesiones(), 
                                        'nomTrata' => $personaTratamiento->getTratamiento()->getNombre()
                                    );
            
            $this->get('bitacora')->escribirbitacora("Se registro una nueva sesion de tratamiento", $usuario->getId());
            
            $response = new JsonResponse();
            $response->setData(array(
                                'exito'       => '1',
                                'seguimiento' =>  $seguimiento->getNumSesion(),
                                'personaTratamiento' => $sesionTratamiento,
                               ));  
            
            return $response; 
        } else {    
            return new Response('0');              
        }     
    }
    
    /**
     * @Route("/ingresar_imagenes_sesiones/get", name="ingresar_foto_venta", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistrarFotoAction(Request $request) {
            //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador
            $nombreimagen2=" ";
            $idConsulta = $request->get('id');
            $dataForm = $request->get('frm');
            $personaId = $_POST["empresaId"];
            
           
            //toca hacer un for para iterar los elementos del file para los diferentes archivos
            for($i=0;$i<count($_FILES['file']['name']);$i++){
                $nombreimagen=$_FILES['file']['name'][$i];    

                $tipo = $_FILES['file']['type'][$i];
                $extension= explode('/',$tipo);
                $nombreimagen2.=".".$extension[1];
            
                if ($nombreimagen != null){
                    $em = $this->getDoctrine()->getManager();
                    $imagen = new ImagenConsulta();
                    
                    die();
                    $imagen->setConsulta();
                    
                    //Direccion fisica del la imagen  
                    $path1 = $this->container->getParameter('photo.tmp');

                    $path = "Photos/perfil/E";
                    $fecha = date('Y-m-d His');

                    $nombreArchivo = $nombreimagen."-".$fecha.$nombreimagen2;

                    $nombreBASE=$path.$nombreArchivo;
                    $nombreBASE=str_replace(" ","", $nombreBASE);
                    $nombreSERVER =str_replace(" ","", $nombreArchivo);

                    $resultado = move_uploaded_file($_FILES["file"]["tmp_name"][$i], $path1.$nombreSERVER);

                    if ($resultado){

                    }else{
                             $data['servidor'] = "No se pudo mover la imagen al servidor";
                    }
                }
                else{
                    //$data['imagen'] = "Imagen invalida";
                }
            }
                     
            //return new Response(json_encode($data));
            return new Response(json_encode(0));            
    }
}
