<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Consulta;
use DGPlusbelleBundle\Entity\Expediente;
use DGPlusbelleBundle\Entity\HistorialClinico;
use DGPlusbelleBundle\Entity\ConsultaProducto;
use DGPlusbelleBundle\Form\ConsultaType;
use DGPlusbelleBundle\Form\ConsultaConPacienteType;
use DGPlusbelleBundle\Form\ConsultaProductoType;

/**
 * Consulta controller.
 *
 * @Route("/admin/consulta")
 */
class ConsultaController extends Controller
{
    public  $tipo=0;
    /**
     * Lists all Consulta de emergencia entities.
     *
     * @Route("/", name="admin_consulta")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Consulta();
        $this->tipo=1;
        $form   = $this->createCreateForm($entity,$this->tipo);
        $entities = $em->getRepository('DGPlusbelleBundle:Consulta')->findAll();

        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
            'tipo'  => 1,
        );
    }
    
    /**
     * Lists all Consulta diaria entities.
     *
     * @Route("/diaria", name="admin_consulta_diaria")
     * @Method("GET")
     * @Template("DGPlusbelleBundle:Consulta:index.html.twig")
     */
    public function indexDiariaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Consulta();
        $this->tipo=2;
        $form   = $this->createCreateForm($entity,$this->tipo);
        //$entities = $em->getRepository('DGPlusbelleBundle:Consulta')->findAll();
        $dql = "SELECT c FROM DGPlusbelleBundle:Consulta c WHERE c.tipoConsulta= :tipo";
        $entities = $em->createQuery($dql)
                       ->setParameter('tipo',1)
                       ->getResult();
               //var_dump($entities);
        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
            'tipo'  => 2,
        );
    }
    
    /**
     * Creates a new Consulta entity.
     *
     * @Route("/", name="admin_consulta_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Consulta:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Consulta();
        $em = $this->getDoctrine()->getManager();
        //Obtiene el usuario
        $id = $request->get('id');
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        //Entidades para insertar en el proceso de la consulta de emergencia
        $historial = new HistorialClinico();
        $expediente = new Expediente();
        
        
        //Seteo de valores
        $expediente->setFechaCreacion(new \DateTime('now'));
        $expediente->setHoraCreacion(new \DateTime('now'));
        $expediente->setEstado(true);
        //$historial->setConsulta($entity);
        
        
        
        //Tipo de consulta actica, emergencia
        $dql = "SELECT tc FROM DGPlusbelleBundle:TipoConsulta tc WHERE tc.estado = :estado AND tc.id=:id";
        $tipoConsulta= $em->createQuery($dql)
                       ->setParameters(array('estado'=>1,'id'=>1))
                       ->getResult();
               //var_dump($tipoConsulta[0]);
               //die();
        $tipoConsulta = $tipoConsulta[0];
        //var_dump($tipoConsulta);
               //die();
        $entity->setTipoConsulta($tipoConsulta);
        //var_dump($this->tipo);
        
        $form = $this->createCreateForm($entity,$id);
        $form->handleRequest($request);
        
       // $producto = $form->get('producto')->getData();
       // $indicaciones = $form->get('indicaciones')->getData();
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $paciente = $entity->getPaciente();
            $paciente->setEstado(true);
            
            $apellido = $paciente->getPersona()->getApellidos();
            $nombre= $paciente->getPersona()->getNombres();
            
            
            $dql = "SELECT p.id, exp.numero FROM DGPlusbelleBundle:Paciente p "
                    . "JOIN p.expediente exp WHERE p.id=:id ";
            $exp = $em->createQuery($dql)
                       ->setParameter('id',$paciente->getId())
                       ->getResult();
            
            //var_dump($exp);
            //$paciente
            //die();
            if(count($exp)==0){
                //Generacion del numero de expediente
                $numeroExp = $nombre[0].$apellido[0].date("Y");
                $dql = "SELECT COUNT(exp)+1 FROM DGPlusbelleBundle:Expediente exp WHERE exp.numero LIKE :numero";

                $num = $em->createQuery($dql)
                           ->setParameter('numero','%'.$numeroExp.'%')
                           ->getResult();
                //var_dump($user);
                $numString = $num[0]["1"];
                //var_dump($numString);

                switch($numString){
                    case 1:
                            $numeroExp .= "00".$numString;
                        break;
                    case 2:
                            $numeroExp .= "0".$numString;
                        break;
                    case 3:
                            $numeroExp .= $numString;
                        break;
                }
                //var_dump($numeroExp);
                //die();
                $expediente->setNumero($numeroExp);
                $expediente->setPaciente($paciente);
                $expediente->setUsuario($user);
                $em->persist($expediente);
                $em->flush();
            }
            
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $empleado = $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('persona'=>$usuario->getPersona()->getId()));
            $entity->setEmpleado($empleado[0]);

            //$historial->setConsulta($consulta);
            //$historial->setExpediente($expediente);
            
            $em->persist($entity);
            $em->flush();
            
            /*  if($producto){
                $this->establecerConsultaProducto($entity, $producto, $indicaciones);
            } */
            
            $empleados=$this->verificarComision($usuario);
            
            if($empleados[0]['suma'] >= $empleados[0]['meta'] && !$empleados[0]['comisionCompleta']){
                $this->get('envio_correo')->sendEmail($empleados[0]['email'],"","","","cumplio su objetivo");
                $empComision = $em->getRepository('DGPlusbelleBundle:Empleado')->find($empleado[0]->getId());
                $empComision->setComisionCompleta(1);
                //var_dump($empComision);
                //die();
                $em->persist($empComision);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('admin_consulta'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Consulta entity.
     *
     * @param Consulta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Consulta $entity,$tipo)
    {
        if($tipo==1){
            $form = $this->createForm(new ConsultaType(), $entity, array(
                'action' => $this->generateUrl('admin_consulta_create', array('id' => 1)),
                'method' => 'POST',
            ));
        }
        else{
            $form = $this->createForm(new ConsultaConPacienteType(), $entity, array(
                'action' => $this->generateUrl('admin_consulta_create', array('id' => 2)),
                'method' => 'POST',
            ));
        }

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Consulta entity.
     *
     * @Route("/new", name="admin_consulta_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Consulta();
        $form   = $this->createCreateForm($entity,1);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
    
    //
    
    /**
     * Displays a form to create a new Consulta entity.
     *
     * @Route("/newconpaciente", name="admin_consulta_nueva_paciente")
     * @Method("GET")
     * @Template()
     */
    public function newConPacienteAction()
    {
        $entity = new Consulta();
        
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del paciente
        $request = $this->getRequest();
        $id= $request->get('id');
        
        //Busqueda del paciente
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        //Seteo del paciente en la entidad
        $entity->setPaciente($paciente);
        
        $form   = $this->createCreateForm($entity,2);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    

    /**
     * Finds and displays a Consulta entity.
     *
     * @Route("/{id}", name="admin_consulta_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Consulta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Consulta entity.
     *
     * @Route("/{id}/edit", name="admin_consulta_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Consulta entity.');
        }

        
        
         // Create an array of the current Tag objects in the database 
        foreach ($entity->getPlacas() as $placa) { $originalPlacas[] = $placa; }
        $editForm = $this->createForm(new ConsultaType(), $entity); 
        $deleteForm = $this->createDeleteForm($id);
        // filter $originalTags to contain tags no longer present 
        foreach ($entity->getPlacas() as $placa) { 
            foreach ($originalPlacas as $key => $toDel) { 
                if ($toDel->getId() === $placa->getId()) {
                    unset($originalPlacas[$key]); 
                    
                } } }

        
        
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    
    /**
     * Displays a form to edit an existing Consulta entity.
     *
     * @Route("/{id}/edit", name="admin_consulta_edit")
     * @Method("GET")
     * @Template()
     */
    public function editConPacienteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Consulta entity.');
        }

        
        
         // Create an array of the current Tag objects in the database 
        foreach ($entity->getPlacas() as $placa) { $originalPlacas[] = $placa; }
        $editForm = $this->createForm(new ConsultaType(), $entity); 
        $deleteForm = $this->createDeleteForm($id);
        // filter $originalTags to contain tags no longer present 
        foreach ($entity->getPlacas() as $placa) { 
            foreach ($originalPlacas as $key => $toDel) { 
                if ($toDel->getId() === $placa->getId()) {
                    unset($originalPlacas[$key]); 
                    
                } } }

        
        
        $editForm = $this->createEditForm($entity,2);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Consulta entity.
    *
    * @param Consulta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Consulta $entity,$tipo)
    {
        if($tipo==1){
            $form = $this->createForm(new ConsultaConPacienteType(), $entity, array(
                'action' => $this->generateUrl('admin_consulta_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));
        }
        else{
            $form = $this->createForm(new ConsultaConPacienteType(), $entity, array(
                'action' => $this->generateUrl('admin_consulta_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )); 
        }
        

        $form->add('submit', 'submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }
    /**
     * Edits an existing Consulta entity.
     *
     * @Route("/{id}", name="admin_consulta_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Consulta:editconpaciente.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Consulta entity.');
        }
        
        $originalTags = array();
                // Create an array of the current Tag objects in the database 
                foreach ($entity->getPlacas() as $tag) { 
                    $originalTags[] = $tag; 
                }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity,2);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            // filter $originalTags to contain tags no longer present 
                    foreach ($entity->getPlacas() as $tag) { 
                        foreach ($originalTags as $key => $toDel) { 
                            if ($toDel->getId() === $tag->getId()) { 
                                //echo $originalTags[$key];
                                unset($originalTags[$key]); 
                                
                            } 
                        } 
                    }
                   // die();
                    // remove the relationship between the tag and the Task
                    /*foreach ($originalTags as $tag) {
                        if (false === $entity->getPlacas()->contains($tag)) {
                            // remove the Task from the Tag
                            //$tag->getConsulta()->removeElement($entity);
                            unset($originalTags[$key]); 
                            // if it was a many-to-one relationship, remove the relationship like this
                            // $tag->setTask(null);

                            $em->persist($tag);

                            // if you wanted to delete the Tag entirely, you can also do that
                            // $em->remove($tag);
                        }
                    }
                */
                // remove the relationship between the tag and the Task 
                    foreach ($originalTags as $tag) { 
                    // remove the Task from the Tag // 
                    //$tag->getPlacas()->removeElement($task);
                
        // if it were a ManyToOne relationship, remove the relationship like this // 
                    //$tag->setTask(null);
                
                        
                        
                         // if you wanted to delete the Tag entirely, you can also do that 
                        $em->remove($tag);
                        //$em->persist($tag);
                        
            
            
                        
            
        }
            $em->flush();
            return $this->redirect($this->generateUrl('admin_consulta'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Consulta entity.
     *
     * @Route("/{id}", name="admin_consulta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Consulta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_consulta'));
    }

    /**
     * Creates a form to delete a Consulta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_consulta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Metodo que sirve para establecer los productos medicados en una consulta
     *
     * @param \DGPlusbelleBundle\Entity\Consulta $idConsulta
     * @param \Doctrine\Common\Collections\ArrayCollection $producto
     * @param string
     *
     */
    private function establecerConsultaProducto(\DGPlusbelleBundle\Entity\Consulta $idConsulta,
                                             \Doctrine\Common\Collections\ArrayCollection $producto, $indicaciones)
    {
        foreach ($producto as $idProducto)
        {
            $consulta_prod = new ConsultaProducto();
            $consulta_prod->setProducto($idProducto);
            $consulta_prod->setConsulta($idConsulta);
            $consulta_prod->setIndicaciones($indicaciones);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($consulta_prod);
            $em->flush();
        }    
    }
    
    
    /**
     * Lista todas las consultas de un paciente
     *
     * @Route("/historialconsulta/", name="admin_historial_consulta")
     * @Method("GET")
     * @Template()
     */
    public function historialConsultaAction(){
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del id
        $request = $this->getRequest();
        $idPaciente= $request->get('id');    
        //var_dump($entity->getPaciente()->getExpediente());
        $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->findBy(array('paciente'=>$idPaciente));
        //$idPaciente = $entity->getPaciente()->getId();
        
        //var_dump($entity[0]->getPaciente());
        //var_dump($entity);
        $fecha = $entity[0]->getPaciente()->getFechaNacimiento()->format("Y-m-d");
        //var_dump($fecha);
        //Calculo de la edad
        list($Y,$m,$d) = explode("-",$fecha);
        $edad = date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y;        
        //$dias = $em->createQuery($dql)
                //->setParameter('idEmp', $idEmp)
                //->getArrayResult();
        //var_dump($consultas);
        /*$stmt = $this->getDoctrine()->getEntityManager()
            ->getConnection()
             ->prepare('select paquete.id, paquete.nombre, paquete_tratamiento.num_sesiones from paciente natural join persona natural join venta_paquete natural join paquete natural join paquete_tratamiento where paciente.id = 1');
        $stmt->execute();
        $result = $stmt->fetchAll();
        */
        //echo $idPaciente;
        
        $paquetes = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->findBy(array('paciente'=>$idPaciente));
        
        $dql = "SELECT count(vp) FROM DGPlusbelleBundle:VentaPaquete vp"
               . " WHERE vp.paciente=:paciente";
        $totalPaquetes = $em->createQuery($dql)
                ->setParameter('paciente', $idPaciente)
                ->getArrayResult();
        
        $dql = "SELECT count(c) FROM DGPlusbelleBundle:Consulta c"
               . " WHERE c.paciente=:paciente";
        $totalTratamientos = $em->createQuery($dql)
                ->setParameter('paciente', $idPaciente)
                ->getArrayResult();
        //var_dump($totalPaquetes[0][1]);
        //var_dump($totalTratamientos[0][1]);
        
        
        $empleados=$this->verificarComision(null);
        
        
        
        //var_dump($empleados);
        
        //var_dump();
        //sendEmail($to, $cc, $bcc,$replay, $body){
        
        
        
        //$comision;
// Formato: dd-mm-yy
//echo calcular_edad(“01-10-1989″); // Resultado: 21
        return array(
            //'entities' => $entities,
            'entity' => $entity,
            'totalTratamientos'=> $totalTratamientos[0][1],//grafica de estadisticas
            'totalPaquetes'=> $totalPaquetes[0][1],//grafica de estadisticas
            'edad' => $edad,
            'consultas' => $entity,
            'paquetes' => $paquetes,
            'empleados' => $empleados,
            );
    }
    
    
    function verificarComision($id){
        $em = $this->getDoctrine()->getManager();
        if($id!=null){//Un empleado especifico
            $dql = "SELECT emp.id, com.meta, emp.foto, p.nombres, p.apellidos,emp.comisionCompleta,p.email "
                    . "FROM DGPlusbelleBundle:Empleado emp "
                    . "JOIN emp.persona p "
                    . "JOIN emp.comision com "
                    . "WHERE emp.estado=true "
                    . "AND emp.id =:idEmpleado";
            $empleados= $em->createQuery($dql)
                       ->setParameter('idEmpleado',$id)
                       ->getResult();
        }
        else{//Todos los empleados
            $dql = "SELECT emp.id, com.meta, emp.foto, p.nombres, p.apellidos,emp.comisionCompleta,p.email "
                    . "FROM DGPlusbelleBundle:Empleado emp "
                    . "JOIN emp.persona p "
                    . "JOIN emp.comision com "
                    . "WHERE emp.estado=true ";
            $empleados= $em->createQuery($dql)
                        ->getResult();
        }
            //$empleados= $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('estado'=>true));

            //var_dump($empleados);
            $mes= date('m');
            if($mes<10){
                $mes = "0".$mes;
            }
            //$mes = '02';
            foreach($empleados as $key=>$empleado){
                //var_dump($empleado);
                $dql = "SELECT sum(t.costo)"
                    . " FROM"
                    . " DGPlusbelleBundle:Consulta c"
                    . " JOIN c.tratamiento t"
                    . " JOIN c.empleado emp"
                    . " WHERE c.fechaConsulta LIKE :mes AND emp.estado=true AND emp.id=:idEmpleado";
                $comision = $em->createQuery($dql)
                       ->setParameters(array('idEmpleado'=>$empleado['id'],'mes'=>'_____'.$mes.'___'))
                       ->getResult();

                //var_dump($comision);
                $empleados[$key]['suma']= $comision[0][1];
                $porcentaje = 0;
                if($empleados[$key]['suma']!=null){
                    if($empleados[$key]['meta']>=$empleados[$key]['suma'] ){
                        $porcentaje = ($empleados[$key]['suma']/$empleados[$key]['meta'])*100;
                    }
                    else{
                        if($empleados[$key]['meta']<$empleados[$key]['suma']){
                            $porcentaje = 100; 
                        }
                    }
                }
                $empleados[$key]['porcentaje']= $porcentaje;
                //Se verifica que el empleado ya cumplio con la meta y si el correo ya fue enviado
                //if($empleados[$key]['suma'] >= $empleados[$key]['meta'] && !$empleados[$key]['comisionCompleta'] && $id!=null){
                    //$this->get('envio_correo')->sendEmail("77456982@sms.claro.com.sv","","","","prueba1");
                    //$this->get('envio_correo')->sendEmail($empleados[$key]['email'],"","","","");
                //}
                
            }
            
            //Envio de sms desde correo
            /*
                $this->get('envio_correo')->sendEmail("75061915@sms.claro.com.sv","","","","prueba1");
                $this->get('envio_correo')->sendEmail("71727845@sms.claro.com.sv","","","","prueba1");
                $this->get('envio_correo')->sendEmail("70550768@sms.claro.com.sv","","","","prueba1");
            */
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            //var_dump($usuario->getPersona()->getId());
            //var_dump($empleados);
            /**/
        
        return $empleados;
    }
    
    
    
    
    
}
