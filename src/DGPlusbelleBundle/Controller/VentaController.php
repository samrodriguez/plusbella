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
        
        $idVpaquete= $request->get('id');  
        $idVentaPaquete=  substr($idVpaquete, 2);
        $ventaPaquete = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($idVentaPaquete);
        
        $persona = $em->getRepository('DGPlusbelleBundle:Persona')->find($ventaPaquete->getPaciente()->getId());
        $paciente= $em->getRepository('DGPlusbelleBundle:Paciente')->findOneBy(array('persona' => $persona));
        
        $idPaciente = $paciente->getId();
        
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
        
        $ventaPaqueteId = $ventaPaquete->getId();
        $regnoeditpaquete = array();
        $regnoedittratamiento = array();
        $idtratamientos = array();      
        
        $tratamientos = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findBy(array('ventaPaquete' => $ventaPaqueteId));
        
        foreach ($tratamientos as $trat){
            $idtrat = $trat->getTratamiento()->getId();
            $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findOneBy(array('tratamiento' => $idtrat,
                                                                                                    'idVentaPaquete' => $ventaPaqueteId
                                                                                                ));
            if($seguimiento->getNumSesion() < $trat->getNumSesiones()){
                array_push($idtratamientos, $idtrat); 
            }            
        }
        
        $dql = "SELECT t.id, t.nombre FROM DGPlusbelleBundle:Tratamiento t "
                    . "WHERE t.id IN (:ids) ";
            $tratVenta = $em->createQuery($dql)
                       ->setParameter('ids', $idtratamientos)
                       ->getResult();
        
        $rsm = new ResultSetMapping();
        
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
                . "inner join detalle_venta_paquete pt on ven.id = pt.venta_paquete "
                . "inner join tratamiento tra on pt.tratamiento = tra.id "
                . "left outer join descuento des on ven.descuento = des.id "
                . "where ven.id = '$ventaPaqueteId' and seg.tratamiento = pt.tratamiento";

        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('nomPaquete','nomPaquete');
        $rsm->addScalarResult('ntrata','ntrata');
        $rsm->addScalarResult('sesiones','sesiones');
        $rsm->addScalarResult('numSesion','numSesion');

        $sesionesVenta = $em->createNativeQuery($sql, $rsm)
                ->getResult();
        
        $ventaPaquetes = $em->getRepository('DGPlusbelleBundle:Paquete')->findBy(array('estado' => true));
        $ventaTratamientos = $em->getRepository('DGPlusbelleBundle:Tratamiento')->findBy(array('estado' => true));
        $sucursales = $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array('estado' => true));
        $empleadosVenta = $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('estado' => true));
        $descuentos = $em->getRepository('DGPlusbelleBundle:Descuento')->findBy(array('estado' => true));
        
        $rsm2 = new ResultSetMapping();
//        $em = $this->getDoctrine()->getManager();            

        $sql2 = "select cast(sum(abo.monto) as decimal(36,2)) abonos "
                . "from abono abo inner join venta_paquete vp on abo.venta_paquete = vp.id "
                . "where vp.id = '$idVentaPaquete'";

        $rsm2->addScalarResult('abonos','abonos');
        
        $abonos = $em->createNativeQuery($sql2, $rsm2)
                ->getSingleResult();
        
        $dql = "SELECT img "
                . "FROM DGPlusbelleBundle:ImagenTratamiento img "
                . "JOIN img.sesionTratamiento ses "
                . "JOIN ses.ventaPaquete ven "
                . "WHERE ven.id = :idVentaTratamiento";

        $archivos = $em->createQuery($dql)
                    ->setParameter('idVentaTratamiento', $idVentaPaquete)
                    ->getResult();    
        
        return array(
            'edad'                => $edad,
            'paciente'            => $paciente,
            'expediente'          => $expnum,
            'paquetesnoedit'      => $regnoeditpaquete,
            'tratamientosnoedit'  => $regnoedittratamiento,
            'ventaPaquetes'       => $ventaPaquetes,
            'ventaPaquete'        => $ventaPaquete,
            //'ventaTratamientos'  => $ventaTratamientos,
            'tratVenta'           => $tratVenta,
            'sucursales'          => $sucursales,
            'empleadosVenta'      => $empleadosVenta,
            'descuentos'          => $descuentos,
            'sesionesVenta'       => $sesionesVenta,
            'abonos'              => $abonos,
            'idPaciente'          => $idPaciente,
           'archivos'            => $archivos
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
        
        $idVtrata= $request->get('id');  
        $idVentaTratamiento=  substr($idVtrata, 2);
        $personaTratamiento = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($idVentaTratamiento);
        
        $persona = $em->getRepository('DGPlusbelleBundle:Persona')->find($personaTratamiento->getPaciente()->getId());
        $paciente= $em->getRepository('DGPlusbelleBundle:Paciente')->findOneBy(array('persona' => $persona));
        
        
        //$archivos = $em->getRepository('DGPlusbelleBundle:ImagenTratammiento')->findBy(array('consulta'=>$idconsulta));
//        $idPacient= $request->get('id');  
//        $idPaciente=  substr($idPacient, 1);
//        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        
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
        
        $CompraPaciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($paciente->getId());
        $paciente = $CompraPaciente;
        
        $regnoeditpaquete = array();
        $regnoedittratamiento = array();

        $ventaPaquetes = $em->getRepository('DGPlusbelleBundle:Paquete')->findBy(array('estado' => true));
        $ventaTratamientos = $em->getRepository('DGPlusbelleBundle:Tratamiento')->findBy(array('estado' => true));
        $sucursales = $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array('estado' => true));
        $empleadosVenta = $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('estado' => true));
        $descuentos = $em->getRepository('DGPlusbelleBundle:Descuento')->findBy(array('estado' => true));
        
        $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findOneBy(array('idPersonaTratamiento' => $personaTratamiento));
        
        $rsm2 = new ResultSetMapping();
            $sql2 = "select cast(sum(abo.monto) as decimal(36,2)) abonos, count(abo.monto) cuotas "
                    . "from abono abo inner join persona_tratamiento p on abo.persona_tratamiento = p.id "
                    . "where p.id = '$idVentaTratamiento'";
            
            $rsm2->addScalarResult('abonos','abonos');
            $rsm2->addScalarResult('cuotas','cuotas');
            
            $abonos = $em->createNativeQuery($sql2, $rsm2)
                    ->getSingleResult();
        
        $dql = "SELECT img "
                . "FROM DGPlusbelleBundle:ImagenTratamiento img "
                . "JOIN img.sesionVentaTratamiento ses "
                . "JOIN ses.personaTratamiento tra "
                . "WHERE tra.id =  :idVentaTratamiento";

        $archivos = $em->createQuery($dql)
                    ->setParameter('idVentaTratamiento', $idVentaTratamiento)
                    ->getResult();    
            
            
        return array(
            'edad'               => $edad,
            'paciente'           => $paciente,
            'expediente'         => $expnum,
            'paquetesnoedit'     => $regnoeditpaquete,
            'tratamientosnoedit' => $regnoedittratamiento,
            'ventaPaquetes'      => $ventaPaquetes,
            'ventaTratamientos'  => $ventaTratamientos,
            'sucursales'         => $sucursales,
            'empleadosVenta'     => $empleadosVenta,
            'descuentos'         => $descuentos,
            'personaTratamiento' => $personaTratamiento,
            'seguimiento'        => $seguimiento,
            'abonos'             => $abonos,
            'idPaciente'         =>$paciente->getId(),
            'archivos'           => $archivos
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
            
            $idtratamientos = array();    
        
            $dvtratamientos = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findBy(array('ventaPaquete' => $ventaPaquete->getId()));

            foreach ($dvtratamientos as $trat){
                $idtrat = $trat->getTratamiento()->getId();
                $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findOneBy(array('tratamiento' => $idtrat,
                                                                                                        'idVentaPaquete' => $ventaPaquete->getId()
                                                                                                    ));

                if($seguimiento->getNumSesion() < $trat->getNumSesiones()){
                    array_push($idtratamientos, $idtrat); 
                }            
            }

            $dql = "SELECT t.id, t.nombre FROM DGPlusbelleBundle:Tratamiento t "
                        . "WHERE t.id IN (:ids) ";
                $tratVenta = $em->createQuery($dql)
                           ->setParameter('ids', $idtratamientos)
                           ->getResult();
            
            
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
                . "inner join detalle_venta_paquete pt on ven.id = pt.venta_paquete "
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
                                'tratVenta' => $tratVenta,
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
     * @Route("/tratamiento/ingresar_imagenes_sesiones/get", name="ingresar_foto_venta", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistrarFotoSesionTratamientoAction(Request $request) {
            //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador
            $nombreimagen2=" ";
            $idsesion = $request->get('id');
            $dataForm = $request->get('frm');
            
            $idsesion = $_POST["idSesion"];
            
            $em = $this->getDoctrine()->getManager();
            
            $personaTratamiento = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($idsesion);
            
            $sesiontratamiento = $em->getRepository('DGPlusbelleBundle:SesionVentaTratamiento')->findBy(array('personaTratamiento' => $personaTratamiento));
            
            $sesiontratamientoId = array();
            foreach ($sesiontratamiento as $key => $value) {
                array_push($sesiontratamientoId, $value->getId());
            }
            
            $sesiontrataId = max($sesiontratamientoId);
            $sesion = $em->getRepository('DGPlusbelleBundle:SesionVentaTratamiento')->find($sesiontrataId);
            
            for($i=0;$i<count($_FILES['file']['name']);$i++){
                $nombreimagen=$_FILES['file']['name'][$i];    

                $tipo = $_FILES['file']['type'][$i];
                $extension= explode('/',$tipo);
                $nombreimagen2.=".".$extension[1];
            
                if ($nombreimagen != null){
                    
                    $imagen = new \DGPlusbelleBundle\Entity\ImagenTratamiento();
                    $imagen->setSesionVentaTratamiento($sesion);
                    
                    //Direccion fisica del la imagen  
                    $path1 = $this->container->getParameter('photo.tmp');

                    $path = "Photos/perfil/E";
                    $fecha = date('Y-m-d His');

                    $nombreArchivo = $nombreimagen."-".$fecha.$nombreimagen2;

                    $nombreBASE=$path.$nombreArchivo;
                    $nombreBASE=str_replace(" ","", $nombreBASE);
                    $nombreSERVER =str_replace(" ","", $nombreArchivo);
                    $imagen->setFotoAntes($nombreSERVER);
                    $resultado = move_uploaded_file($_FILES["file"]["tmp_name"][$i], $path1.$nombreSERVER);
                    $em->persist($imagen);
                    $em->flush();

                    if ($resultado){
                    
                    }else{
                        $data['servidor'] = "No se pudo mover la imagen al servidor";
                    }


                }
                else{

                }
            }
         
            
            //return new Response(json_encode($data));
            return new Response(json_encode(0));
    }
    
    /**
     * @Route("/paquete/ingresar_imagenes_sesiones/get", name="ingresar_foto_venta_paquete", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistrarFotoSesionPaqueteAction(Request $request) {
            //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador
            $nombreimagen2=" ";
            $idsesion = $request->get('id');
            $dataForm = $request->get('frm');
            
            $idsesion = $_POST["idSesion"];
            
            $em = $this->getDoctrine()->getManager();
            
            $ventaPaquete = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($idsesion);
            
            $sesiontratamiento = $em->getRepository('DGPlusbelleBundle:SesionTratamiento')->findBy(array('ventaPaquete' => $ventaPaquete));
            
            $sesiontratamientoId = array();
            foreach ($sesiontratamiento as $key => $value) {
                array_push($sesiontratamientoId, $value->getId());
            }
            
            $sesiontrataId = max($sesiontratamientoId);
            $sesion = $em->getRepository('DGPlusbelleBundle:SesionTratamiento')->find($sesiontrataId);
            
            for($i=0;$i<count($_FILES['file']['name']);$i++){
                $nombreimagen=$_FILES['file']['name'][$i];    

                $tipo = $_FILES['file']['type'][$i];
                $extension= explode('/',$tipo);
                $nombreimagen2.=".".$extension[1];
            
                if ($nombreimagen != null){
                    
                    $imagen = new \DGPlusbelleBundle\Entity\ImagenTratamiento();
                    $imagen->setSesionVentaTratamiento($sesion);
                    
                    //Direccion fisica del la imagen  
                    $path1 = $this->container->getParameter('photo.tmp');

                    $path = "Photos/perfil/E";
                    $fecha = date('Y-m-d His');

                    $nombreArchivo = $nombreimagen."-".$fecha.$nombreimagen2;

                    $nombreBASE=$path.$nombreArchivo;
                    $nombreBASE=str_replace(" ","", $nombreBASE);
                    $nombreSERVER =str_replace(" ","", $nombreArchivo);
                    $imagen->setFotoAntes($nombreSERVER);
                    $resultado = move_uploaded_file($_FILES["file"]["tmp_name"][$i], $path1.$nombreSERVER);
                    $em->persist($imagen);
                    $em->flush();

                    if ($resultado){
                    
                    }else{
                        $data['servidor'] = "No se pudo mover la imagen al servidor";
                    }


                }
                else{

                }
            }
         
            
            //return new Response(json_encode($data));
            return new Response(json_encode(0));
    }
    
    /**
    * Ajax utilizado para registrar un nuevo abono de tratamiento
    *  
    * @Route("/abono/tratamiento/set", name="admin_abonotratamiento_nuevo")
    */
    public function registroAbonoTratamientoAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $em = $this->getDoctrine()->getManager();
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            
            $id = $this->get('request')->request->get('id');
            $sucursalId = $this->get('request')->request->get('sucursal');
            $empleadoId = $this->get('request')->request->get('empleado');
            $monto = $this->get('request')->request->get('monto');
            $descripcion = $this->get('request')->request->get('descripcion');
            $id_personatratamiento = $this->get('request')->request->get('id_personatratamiento');
            
            $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
            $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursalId);
            $empleado = $em->getRepository('DGPlusbelleBundle:Empleado')->find($empleadoId);
            $personaTratamiento = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($id_personatratamiento);
            
            $entity = new \DGPlusbelleBundle\Entity\Abono();
            
            $entity->setPaciente($paciente);
            $entity->setEmpleado($empleado);
            $entity->setSucursal($sucursal);
            $entity->setFechaAbono(new \DateTime('now'));
            $entity->setVentaPaquete(null); 
            $entity->setPersonaTratamiento($personaTratamiento);
            $entity->setMonto($monto);
            $entity->setDescripcion($descripcion);
            
            $em->persist($entity);
            $em->flush();

            $rsm = new ResultSetMapping();
            $rsm2 = new ResultSetMapping();
            $sql2 = "select cast(sum(abo.monto) as decimal(36,2)) abonos, count(abo.monto) cuotas "
                    . "from abono abo inner join persona_tratamiento p on abo.persona_tratamiento = p.id "
                    . "where p.id = '$id'";
            
            $rsm2->addScalarResult('abonos','abonos');
            $rsm2->addScalarResult('cuotas','cuotas');
            
            $abonos = $em->createNativeQuery($sql2, $rsm2)
                    ->getResult();
            
            $this->get('bitacora')->escribirbitacora("Se registro un nuevo abono de un tratamiento", $usuario->getId());
            
            $response = new JsonResponse();
            $response->setData(array(
                                'exito'    => '1',
                                'abonos'   => $abonos,
                                'abono'    => $entity->getId()
                                ));  
            
            return $response; 
        } else {    
            return new Response('0');              
        }     
    }
    
    /**
    * Ajax utilizado para registrar un nuevo abono de paquete
    *  
    * @Route("/abono/paquete/set", name="admin_abonopaquete_nuevo")
    */
    public function registroAbonoPaqueteAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $em = $this->getDoctrine()->getManager();
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            
            $id = $this->get('request')->request->get('id');
            $sucursalId = $this->get('request')->request->get('sucursal');
            $empleadoId = $this->get('request')->request->get('empleado');
            $monto = $this->get('request')->request->get('monto');
            $descripcion = $this->get('request')->request->get('descripcion');
            $id_ventapaquete = $this->get('request')->request->get('id_ventapaquete');
            
            $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
            $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursalId);
            $empleado = $em->getRepository('DGPlusbelleBundle:Empleado')->find($empleadoId);
            $ventaPaquete = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id_ventapaquete);
            
            $entity = new \DGPlusbelleBundle\Entity\Abono();
            
            $entity->setPaciente($paciente);
            $entity->setEmpleado($empleado);
            $entity->setSucursal($sucursal);
            $entity->setFechaAbono(new \DateTime('now'));
            $entity->setVentaPaquete($ventaPaquete); 
            $entity->setPersonaTratamiento(null);
            $entity->setMonto($monto);
            $entity->setDescripcion($descripcion);
            
            $em->persist($entity);
            $em->flush();

            $this->get('bitacora')->escribirbitacora("Se registro un nuevo abono de un paquete", $usuario->getId());
            
            $response = new JsonResponse();
            $response->setData(array(
                                'exito'   => '1',
                                ));  
            
            return $response; 
        } else {    
            return new Response('0');              
        }     
    }
}
