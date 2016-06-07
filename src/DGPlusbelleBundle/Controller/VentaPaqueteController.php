<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\SeguimientoPaquete;
use DGPlusbelleBundle\Entity\VentaPaquete;
use DGPlusbelleBundle\Entity\Paciente;
use DGPlusbelleBundle\Form\VentaPaqueteType;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * VentaPaquete controller.
 *
 * @Route("/admin/ventapaquete")
 */
class VentaPaqueteController extends Controller
{

    /**
     * Lists all VentaPaquete entities.
     *
     * @Route("/", name="admin_ventapaquete")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new VentaPaquete();
        $form   = $this->createCreateForm($entity);
        $entities = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->findAll();

        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new VentaPaquete entity.
     *
     * @Route("/", name="admin_ventapaquete_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:VentaPaquete:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new VentaPaquete();
       
        //$entity->getFechaVenta();
        $em = $this->getDoctrine()->getManager();//
        
        //Obtener el usuario segun el id
        //$usuario = $em->getRepository('DGPlusbelleBundle:Usuario')->findBy(array("id"=>1));
        $usuario= $this->get('security.token_storage')->getToken()->getUser();

//        $dql = "SELECT suc.id
//            FROM DGPlusbelleBundle:Usuario u
//                    JOIN u.persona per
//                    JOIN per.empleado emp
//                    
//            WHERE u.id=:id";
//        
//        $entities = $em->createQuery($dql)
//                       ->setParameter('id',1)
//                       ->getResult();


//        $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array("id"=>$entities[0]["id"]));
//        $entity->setSucursal($sucursal[0]);
        $entity->setUsuario($usuario);
        //var_dump($usuario);

        //die();

        $entity->setFechaRegistro(new \DateTime('now'));
        $entity->setFechaVenta(new \DateTime('now'));
        $entity->setEstado(1);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $paqueteTratamiento = $em->getRepository('DGPlusbelleBundle:PaqueteTratamiento')->findBy(array('paquete' => $entity->getPaquete()->getId()));
            // var_dump($paqueteTratamiento);
            // die();
            foreach($paqueteTratamiento as $pt){
                $seguimiento = new SeguimientoPaquete;
                $seguimiento->setVentaPaquete($entity);
                $seguimiento->setNumSesion(0);
                $seguimiento->setTratamiento($pt->getTratamiento());
                $em->persist($seguimiento);
                $em->flush();
            }
                
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se registro la venta de un paquete",$usuario->getId());
            
            return $this->redirect($this->generateUrl('admin_ventapaquete'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a VentaPaquete entity.
     *
     * @param VentaPaquete $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(VentaPaquete $entity)
    {
        $form = $this->createForm(new VentaPaqueteType(), $entity, array(
            'action' => $this->generateUrl('admin_ventapaquete_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new VentaPaquete entity.
     *
     * @Route("/new", name="admin_ventapaquete_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new VentaPaquete();
        $em = $this->getDoctrine()->getManager();
        
        //Recuperaci贸n del paciente
        $request = $this->getRequest();
        //$id= $request->get('id');
        //$id = substr($id, 1);
        //Busqueda del paciente
        //$paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        //Seteo del paciente en la entidad
        //$persona=$paciente->getPersona();
        //$entity->setPaciente($persona);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a VentaPaquete entity.
     *
     * @Route("/{id}", name="admin_ventapaquete_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VentaPaquete entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing VentaPaquete entity.
     *
     * @Route("/{id}/edit", name="admin_ventapaquete_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VentaPaquete entity.');
        }
//var_dump($entity);
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'id'=>"P".$entity->getPaciente()->getPaciente()[0]->getId(),
        );
    }

    /**
    * Creates a form to edit a VentaPaquete entity.
    *
    * @param VentaPaquete $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VentaPaquete $entity)
    {
        $form = $this->createForm(new VentaPaqueteType(), $entity, array(
            'action' => $this->generateUrl('admin_ventapaquete_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-primary btn-sm')));

        return $form;
    }
    /**
     * Edits an existing VentaPaquete entity.
     *
     * @Route("/{id}", name="admin_ventapaquete_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:VentaPaquete:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);


        //Obtener el usuario segun el id
        $usuario = $em->getRepository('DGPlusbelleBundle:Usuario')->findBy(array("id"=>1));

        /*$dql = "SELECT suc.id
            FROM DGPlusbelleBundle:Usuario u
                    JOIN u.persona per
                    JOIN per.empleado emp
            WHERE u.id=:id";
        
        $entities = $em->createQuery($dql)
                       ->setParameter('id',1)
                       ->getResult();
*/

        //$sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->findBy(array("id"=>$entities[0]["id"]));
        //$entity->setSucursal($sucursal[0]);
        $entity->setUsuario($usuario[0]);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VentaPaquete entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ventapaquete'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a VentaPaquete entity.
     *
     * @Route("/{id}", name="admin_ventapaquete_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find VentaPaquete entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ventapaquete'));
    }







    /**
     * Creates a form to delete a VentaPaquete entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ventapaquete_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }




    /**
     * Lists all VentaPaquete entities.
     *
     * @Route("/ventapaquete/pacientes", name="admin_ventapaquete_pacientes")
     * @Method("GET")
     * @Template()
     */
    public function ventapaqueteAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Paciente')->findAll();

        return array(
            'pacientes' => $entities,
        );


    }
    
    /**
    * Ajax utilizado para buscar informacion del producto
    *  
    * @Route("/venta/costo_paquete/get", name="admin_costo_paquete")
    */
    public function costoPaqueteAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $em = $this->getDoctrine()->getManager();
            $id = $this->get('request')->request->get('id');
            $entity = $em->getRepository('DGPlusbelleBundle:Paquete')->find($id);
            $paqueteTratamiento = $em->getRepository('DGPlusbelleBundle:PaqueteTratamiento')->findBy(array( 'paquete' => $entity ));

            $exito=array();
            if($entity!=null){
                $exito['regs']=$entity->getCosto();
            }
            else{
                $exito['regs']=-1;
            }
            //var_dump($paqueteTratamiento);
           
            $paqueteTrat = array();
            foreach ($paqueteTratamiento as $key => $value) {
                 $paqueteTrat[$key]['id'] = $value->getId();
                 $paqueteTrat[$key]['idtrat'] = $value->getTratamiento()->getId();
                 $paqueteTrat[$key]['Nombretrat'] = $value->getTratamiento()->getNombre();
                 $paqueteTrat[$key]['sesiones'] = $value->getNumSesiones();
            }
            //var_dump($paqueteTrat);
            $response = new JsonResponse();
            $response->setData(array(
                                'data'       => $exito,
                                'paqueteTrat' => $paqueteTrat
                            )); 
            
            return $response; 
        } else {    
            return new Response('0');              
        } 
   } 
    
   /**
    * Ajax utilizado para buscar informacion del producto
    *  
    * @Route("/registro/nueva_venta/set", name="admin_registro_nueva_venta_paquete")
    */
    public function registrarNuevaVentaPaqueteAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $em = $this->getDoctrine()->getManager();
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            
            $id = $this->get('request')->request->get('id');
            $paqueteId = $this->get('request')->request->get('paquete');
            $sucursalId = $this->get('request')->request->get('sucursal');
            $empleadoId = $this->get('request')->request->get('empleado');
            $costo = $this->get('request')->request->get('costo');
            $cuotas = $this->get('request')->request->get('cuotas');
            $tratamientos = $this->get('request')->request->get('tratamientos');
            $sesiones = $this->get('request')->request->get('sesiones');
            $descuentoId = $this->get('request')->request->get('descuento');
            $observaciones = $this->get('request')->request->get('observaciones');
            
            $ventaPaquete = new VentaPaquete();
            
            $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
            $personaPaciente = $em->getRepository('DGPlusbelleBundle:Persona')->find($paciente->getPersona()->getId());
            $ventaPaquete->setPaciente($personaPaciente);
            //var_dump($empleadoId);
            if(!is_null($empleadoId) && $empleadoId != ''){
                $empleado = $em->getRepository('DGPlusbelleBundle:Empleado')->find($empleadoId);
                $personaEmpleado = $em->getRepository('DGPlusbelleBundle:Persona')->find($empleado->getPersona()->getId());
                $ventaPaquete->setEmpleado($personaEmpleado);
            }
            
            $ventaPaquete->setCosto($costo);
            $ventaPaquete->setCuotas($cuotas);
            
            $paquete = $em->getRepository('DGPlusbelleBundle:Paquete')->find($paqueteId);
            $ventaPaquete->setPaquete($paquete);
           
            $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursalId);
            $ventaPaquete->setSucursal($sucursal);
            
            if(!is_null($descuentoId)){
                $descuento = $em->getRepository('DGPlusbelleBundle:Descuento')->find($descuentoId);
                $ventaPaquete->setDescuento($descuento);
            }
            
            if(!is_null($observaciones)){
                $ventaPaquete->setObservaciones($observaciones);
            }
            
            $ventaPaquete->setUsuario($usuario);
            $ventaPaquete->setFechaRegistro(new \DateTime('now'));
            $ventaPaquete->setFechaVenta(new \DateTime('now'));
            $ventaPaquete->setEstado(1);
            
            $em->persist($ventaPaquete);
            $em->flush();
            
            $paqueteTratamiento = $em->getRepository('DGPlusbelleBundle:PaqueteTratamiento')->findBy(array('paquete' => $ventaPaquete->getPaquete()->getId()));
            
            foreach($paqueteTratamiento as $pt){
                $seguimiento = new SeguimientoPaquete;
                $seguimiento->setVentaPaquete($ventaPaquete);
                $seguimiento->setNumSesion(0);
                $seguimiento->setTratamiento($pt->getTratamiento($ventaPaquete));
                $em->persist($seguimiento);
                $em->flush();
            }
            
            $nomTratamientos = array();
            foreach($tratamientos as $key => $value){
                $detalleVenta = new \DGPlusbelleBundle\Entity\DetalleVentaPaquete();
                        
                $paqT = $em->getRepository('DGPlusbelleBundle:PaqueteTratamiento')->find($value);

                $detalleVenta->setTratamiento($paqT->getTratamiento());
                $detalleVenta->setVentaPaquete($ventaPaquete);
                $detalleVenta->setNumSesiones($sesiones[$key]);

                $em->persist($detalleVenta);
                $em->flush();
                
                $nomTratamientos[$key] = $detalleVenta->getTratamiento()->getNombre();
                $idTratamientos[$key] = $detalleVenta->getTratamiento()->getId();
            }
            
            $idtratamientos = array();    
        
            $vTratamientos = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findBy(array('ventaPaquete' => $ventaPaquete->getId()));

            foreach ($vTratamientos as $trat){
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
            
            if($ventaPaquete->getDescuento()){    
                $totaldesc = ($ventaPaquete->getCosto() * $ventaPaquete->getDescuento()->getPorcentaje()) / 100;
                //$totaldesc = $ventaPaquete->getDescuento()->getPorcentaje();
            } else {
                $totaldesc = 0;
            }
            //var_dump($totaldesc);
            $ventaPaqueteTratamientos = array(
                                        'id' => $ventaPaquete->getId(), 
                                        'costo' => $ventaPaquete->getCosto(), 
                                        'descuento' => $totaldesc, 
                                        'sesiones' => $sesiones,
                                        'tratamientos' => $tratamientos,
                                        'tratVenta' => $tratVenta,
                                        'nomTratamientos' => $nomTratamientos,
                                        'idTratamientos' => $idTratamientos,
                                        'cuotas' => $ventaPaquete->getCuotas()
                                    );
            
            $this->get('bitacora')->escribirbitacora("Se registro una nueva venta del paquete " . $paquete->getNombre(), $usuario->getId());
            
            $response = new JsonResponse();
            $response->setData(array(
                                'exito'       => '1',
                                'paquete' => $paquete->getNombre(),
                                'ventaPaquete' => $ventaPaquete->getId(),
                                'ventaPaqueteTratamientos' => $ventaPaqueteTratamientos
                               ));  
            
            return $response; 
        } else {    
            return new Response('0');              
        } 
   }

   /**
    * Ajax utilizado para buscar informacion del producto
    *  
    * @Route("/registro/venta/edit", name="admin_registro_editar_venta_paquete")
    */
    public function registrarEditarVentaPaqueteAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $em = $this->getDoctrine()->getManager();
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            
            $id = $this->get('request')->request->get('id');
            //$paqueteId = $this->get('request')->request->get('paquete');
            $sucursalId = $this->get('request')->request->get('sucursal');
            $empleadoId = $this->get('request')->request->get('empleado');
            $costo = $this->get('request')->request->get('costo');
            $cuotas = $this->get('request')->request->get('cuotas');
            $observaciones = $this->get('request')->request->get('observaciones');
            $tratamientos = $this->get('request')->request->get('tratamientos');
            $sesiones = $this->get('request')->request->get('sesiones');
            $descuentoId = $this->get('request')->request->get('descuento');
            $id_ventapaquete = $this->get('request')->request->get('id_ventapaquete');
            
            $ventaPaquete = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id_ventapaquete);
            
            $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
            $personaPaciente = $em->getRepository('DGPlusbelleBundle:Persona')->find($paciente->getPersona()->getId());
            $ventaPaquete->setPaciente($personaPaciente);
            
            if(!is_null($empleadoId)){
                $empleado = $em->getRepository('DGPlusbelleBundle:Empleado')->find($empleadoId);
                $personaEmpleado = $em->getRepository('DGPlusbelleBundle:Persona')->find($empleado->getPersona()->getId());
                $ventaPaquete->setEmpleado($personaEmpleado);
            }
            
            if(!is_null($observaciones)){
                $ventaPaquete->setObservaciones($observaciones);
            }
            
            $ventaPaquete->setCosto($costo);
            $ventaPaquete->setCuotas($cuotas);
            
//            $paquete = $em->getRepository('DGPlusbelleBundle:Paquete')->find($paqueteId);
//            $ventaPaquete->setPaquete($paquete);
           
            $sucursal = $em->getRepository('DGPlusbelleBundle:Sucursal')->find($sucursalId);
            $ventaPaquete->setSucursal($sucursal);
            
            if(!is_null($descuentoId)){
                $descuento = $em->getRepository('DGPlusbelleBundle:Descuento')->find($descuentoId);
                $ventaPaquete->setDescuento($descuento);
            }
            
            $em->merge($ventaPaquete);
            $em->flush();
            
            $nomTratamientos = array();
//            foreach($tratamientos as $key => $value){
//                $detalleVenta = new \DGPlusbelleBundle\Entity\DetalleVentaPaquete();
//                        
//                $paqT = $em->getRepository('DGPlusbelleBundle:PaqueteTratamiento')->find($value);
//
//                $detalleVenta->setTratamiento($paqT->getTratamiento());
//                $detalleVenta->setVentaPaquete($ventaPaquete);
//                $detalleVenta->setNumSesiones($sesiones[$key]);
//
//                $em->persist($detalleVenta);
//                $em->flush();
//                
//                $nomTratamientos[$key] = $detalleVenta->getTratamiento()->getNombre();
//                $idTratamientos[$key] = $detalleVenta->getTratamiento()->getId();
//            }
            
            $idtratamientos = array();    
        
            $vTratamientos = $em->getRepository('DGPlusbelleBundle:DetalleVentaPaquete')->findBy(array('ventaPaquete' => $ventaPaquete->getId()));

            foreach ($vTratamientos as $trat){
                $idtrat = $trat->getTratamiento()->getId();
                $seguimiento = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findOneBy(array('tratamiento' => $idtrat,
                                                                                                        'idVentaPaquete' => $ventaPaquete->getId()
                                                                                                    ));
                if($seguimiento->getNumSesion() < $trat->getNumSesiones()){
                    array_push($idtratamientos, $idtrat); 
                    array_push($nomTratamientos, $trat->getTratamiento()->getNombre()); 
                }            

                //$nomTratamientos[$key] = $detalleVenta->getTratamiento()->getNombre();
                //$idTratamientos[$key] = $detalleVenta->getTratamiento()->getId();                
            }

            if($ventaPaquete->getDescuento() != null){
                $descuentoVenta = $ventaPaquete->getDescuento()->getPorcentaje();
            } else {
                $descuentoVenta = 0;
            }
            
            $dql = "SELECT t.id, t.nombre FROM DGPlusbelleBundle:Tratamiento t "
                        . "WHERE t.id IN (:ids) ";
                $tratVenta = $em->createQuery($dql)
                           ->setParameter('ids', $idtratamientos)
                           ->getResult();
            
            $ventaPaqueteTratamientos = array(
                                        'id' => $ventaPaquete->getId(), 
                                        'costo' => $ventaPaquete->getCosto(), 
                                        'sesiones' => $sesiones,
                                        'tratamientos' => $tratamientos,
                                        'tratVenta' => $tratVenta,
                                        'nomTratamientos' => $nomTratamientos,
                                        'descuento' => $descuentoVenta,
                                        'cuotas' => $ventaPaquete->getCuotas()
                                    );
            
            $rsm2 = new ResultSetMapping();
            $ptid = $ventaPaquete->getId();
            $sql2 = "select cast(sum(abo.monto) as decimal(36,2)) abonos, count(abo.monto) cuotas "
                    . "from abono abo inner join venta_paquete p on abo.venta_paquete = p.id "
                    . "where p.id = '$ptid'";
            
            $rsm2->addScalarResult('abonos','abonos');
            $rsm2->addScalarResult('cuotas','cuotas');
            
            $abonos = $em->createNativeQuery($sql2, $rsm2)
                    ->getSingleResult();
            
            $this->get('bitacora')->escribirbitacora("Se edito la venta del paquete " . $ventaPaquete->getPaquete()->getNombre(), $usuario->getId());
            
            $response = new JsonResponse();
            $response->setData(array(
                                'exito'       => '1',
                                'paquete' => $ventaPaquete->getPaquete()->getNombre(),
                                'ventaPaquete' => $ventaPaquete->getId(),
                                'ventaPaqueteTratamientos' => $ventaPaqueteTratamientos,
                                'abonos' => $abonos
                               ));  
            
            return $response; 
        } else {    
            return new Response('0');              
        } 
   }
}
