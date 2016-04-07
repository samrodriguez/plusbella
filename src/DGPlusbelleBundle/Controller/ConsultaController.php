<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Consulta;
use DGPlusbelleBundle\Entity\Expediente;
use DGPlusbelleBundle\Entity\HistorialClinico;
use DGPlusbelleBundle\Entity\HistorialConsulta;
use DGPlusbelleBundle\Entity\ConsultaProducto;
use DGPlusbelleBundle\Entity\ImagenConsulta;
use DGPlusbelleBundle\Form\ConsultaType;
use DGPlusbelleBundle\Form\ConsultaConPacienteType;
use DGPlusbelleBundle\Form\ConsultaProductoType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
        $form   = $this->createCreateForm($entity,$this->tipo,0);
        $entities = $em->getRepository('DGPlusbelleBundle:Consulta')->findAll();

        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
            'tipo'  => 1,
        );
    }
    
    
    /**
     * Lista todos los expediente de los paciente.
     *
     * @Route("/expediente", name="admin_consulta_expediente")
     * @Method("GET")
     * @Template("DGPlusbelleBundle:Consulta:expediente.html.twig")
     */
    public function expedienteAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        
        
        $entities = $em->getRepository('DGPlusbelleBundle:Paciente')->findAll();
        $dql = $dql = "SELECT p.id, exp.numero, per.nombres, per.apellidos  FROM DGPlusbelleBundle:Paciente p"
                . " INNER JOIN p.persona per"
                . " JOIN p.expediente exp";
        $entities = $em->createQuery($dql)
                       //->setParameter('tipo',1)
                       ->getResult();
        //var_dump($entities);
        //var_dump($entities[0]->getExpediente()[0]->getNumero());
        //die();
        return array(
            'pacientes' => $entities,
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
        $form   = $this->createCreateForm($entity,$this->tipo,0);
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
     * @Route("/create/{idpac}", name="admin_consulta_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Consulta:newconpaciente.html.twig")
     */
    public function createAction(Request $request, $idpac)
    {
        $entity = new Consulta();
        $em = $this->getDoctrine()->getManager();
        
        //Obtiene el usuario
        $id= $request->get('id');
        
        $flag = 0;
        $cadena= $request->get('identidad');
        $parameters = $request->request->all();
        
        //Obtener del parametro el valor que se debe usar para programar la consulta
        $accion = $cadena[0];
        
        //Obtener el id del parametro
        $idEntidad = substr($cadena, 1);
        
        $pac = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idpac);
        
        
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        //Entidades para insertar en el proceso de la consulta de emergencia
//        $historial = new HistorialClinico();
        $expediente = new Expediente();
        
        
        //Seteo de valores
        $expediente->setFechaCreacion(new \DateTime('now'));
        $expediente->setHoraCreacion(new \DateTime('now'));
        $expediente->setEstado(true);
        //$historial->setConsulta($entity);
        
        $entity->setFechaConsulta(new \DateTime('now'));
        
        //Tipo de consulta actica, emergencia
        /*$dql = "SELECT tc FROM DGPlusbelleBundle:TipoConsulta tc WHERE tc.estado = :estado AND tc.id=:id";
        $tipoConsulta = $em->createQuery($dql)
                       ->setParameters(array('estado'=>1,'id'=>1))
                       ->getResult();
               //var_dump($tipoConsulta[0]);
               //die();
        $tipoConsulta = $tipoConsulta[0];*/
        //var_dump($tipoConsulta);
               //die();
        //$entity->setTipoConsulta($tipoConsulta);
        //var_dump($this->tipo);
        
        
        if($idpac == -1){
            $paciente_est = $em->getRepository('DGPlusbelleBundle:Paciente')->find($parameters['dgplusbellebundle_consulta']['paciente']);
            
            $entity->setPaciente($paciente_est);
            //var_dump($entity);
            $flag = 1;
            $accion = 'E';
            $pacient = new \DGPlusbelleBundle\Entity\Paciente();
            $form = $this->createCreateForm($entity,3,$idEntidad,$pacient);
        } else {
            $form = $this->createCreateForm($entity,2,$idEntidad,$pac);
        }
            
        $form->handleRequest($request);
        
        
        //$campos = $form->get('campos')->getData();
       // $indicaciones = $form->get('indicaciones')->getData();
        
       // foreach($parameters as $p){
       //     $campos = $parameters->campos;
        //}
        
        //var_dump($parameters['dgplusbellebundle_consulta']['campos']);
        //die();
        //if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $tratamiento = null;
            switch ($accion){
                case 'C':
                    $cita = $em->getRepository('DGPlusbelleBundle:Cita')->find($idEntidad);
                    $cita->setEstado("A");
                    $tratamiento = $cita->getTratamiento();
                    $entity->setCita($cita);
                    $em->persist($cita);
                    $em->flush();
                    break;
                case 'P':
                    //$entity->setCita(null);
                    break;
            }
        
            
            
            
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

                switch(strlen($numString)){
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
            }
            
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $empleado = $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('persona'=>$usuario->getPersona()->getId()));
            $entity->setEmpleado($empleado[0]);

            //$historial->setConsulta($consulta);
            //$historial->setExpediente($expediente);
            
            
            $placas = new ArrayCollection();
            $path = $this->container->getParameter('photo.paciente');
            $i=0;
            foreach($entity->getPlacas2() as $key => $row){
                //var_dump($row);    
                $imagenConsulta = new ImagenConsulta();
                
                if($row->getFile()!=null){
                    //echo "vc";
                    $fecha = date('Y-m-d His');
                    $extension = $row->getFile()->getClientOriginalExtension();
                    $nombreArchivo = "consulta - ".$i." - ".$fecha.".".$extension;

                    //echo $nombreArchivo;
                    //$seguimiento->setFotoAntes($nombreArchivo);

                    $imagenConsulta->setFoto($nombreArchivo);
                    $row->setFoto($nombreArchivo);
                    //$imagenConsulta->setConsulta($entity);
                    //array_push($placas, $imagenConsulta);
                    $row->getFile()->move($path,$nombreArchivo);
                    //$em->merge($seguimiento);
                    $em->persist($row);
                    //$em->flush();
                    $i++;
                }
                //var_dump($row->getFile());  
            }
            //die();
            //$entity->setPlacas2($placas);
            //$entity->setRegistraReceta(1);
            $em->persist($entity);
            $em->flush();
            
            if(isset($parameters['dgplusbellebundle_consulta']['plantilla'])){
                $plantillaid = $parameters['dgplusbellebundle_consulta']['plantilla'];

                //var_dump($parameters);
                //die();
                $dql = "SELECT det.id, det.nombre "
                        . "FROM DGPlusbelleBundle:DetallePlantilla det "
                        . "JOIN det.plantilla pla "
                        . "WHERE pla.id =  :plantillaid";

                $parametros = $em->createQuery($dql)
                            ->setParameter('plantillaid', $plantillaid)
                            ->getResult();


                //$valores = array(); 
                // var_dump($usuario); 

                foreach($parametros as $p){
                    $dataReporte = new HistorialConsulta;
                    $detalle = $em->getRepository('DGPlusbelleBundle:DetallePlantilla')->find($p['id']);

                    $dataReporte->setDetallePlantilla($detalle);       
                    $dataReporte->setConsulta($entity);
                    $dataReporte->setConsultaReceta(null);

                    $nparam = explode(" ", $p['nombre']);
                    //var_dump(count($nparam)); 
                    $lon = count($nparam);
                    if($lon > 1){
                        $pnombre = $nparam[0];
                        foreach($nparam as $key => $par){
                            if($key +1 != $lon){
                                $pnombre .= '_'.$nparam[$key + 1];
                            }
                        }
                        $dataReporte->setValorDetalle($parameters[$pnombre]);
                    } else {
                        $dataReporte->setValorDetalle($parameters[$p['nombre']]);
                    }
                   
                    $em->persist($dataReporte);
                    $em->flush();
                }   
            }
            else {
                
                $esteticaid = $parameters['dgplusbellebundle_consulta']['estetica'];


                $dql = "SELECT det.id, det.nombre, opc.id opcid, opc.nombre opcnom "
                        . "FROM DGPlusbelleBundle:OpcionesDetalleEstetica opc "
                        . "JOIN opc.detalleEstetica det "
                        . "JOIN det.estetica est "
                        . "WHERE est.id =  :esteticaid";

                $parametros = $em->createQuery($dql)
                            ->setParameter('esteticaid', $esteticaid)
                            ->getResult();


                //$valores = array(); 
                //var_dump($parameters); 
                //die();
                foreach($parametros as $p){
                    $dataReporte = new \DGPlusbelleBundle\Entity\HistorialEstetica;
                    
                    $detalle = $em->getRepository('DGPlusbelleBundle:OpcionesDetalleEstetica')->find($p['opcid']);
                    
                    
                    
                    $dataReporte->setdetalleEstetica($detalle);       
                    $dataReporte->setConsulta($entity);
                    //$dataReporte->setConsultaReceta(null);
                    //var_dump($p['opcnom']);
                    $nparam = explode(" ", $p['opcnom']);
                    //var_dump(count($nparam)); 
                    $lon = count($nparam);
                    if($lon > 1){
                        $pnombre = $nparam[0];
                        foreach($nparam as $key => $par){
                            //var_dump($key);
                            if($key +1 != $lon){
                                //var_dump($lon);
                                $pnombre .= '_'.$nparam[$key + 1];
                            }
                        }
                        
                        if(isset($parameters[$pnombre])){
                            $dataReporte->setValor($pnombre);
                            
                            $em->persist($dataReporte);
                            $em->flush();
                        }    
                    } else {
                        if(isset($parameters[$p['opcnom']])){
                            $dataReporte->setValor($p['opcnom']);
                             //var_dump($parameters[$p['opcnom']]); 
                             
                            $em->persist($dataReporte);
                            $em->flush();
                        }
                    }
                
//                    $em->persist($dataReporte);
//                    $em->flush();
                }
                
                if(isset($parameters['corporal'])){
                    $compCorporal = new \DGPlusbelleBundle\Entity\ComposicionCorporal;
                    $estetica = $em->getRepository('DGPlusbelleBundle:Estetica')->find($parameters['dgplusbellebundle_consulta']['estetica']);
                    
                    $compCorporal->setPeso($parameters['corporal']['peso']);
                    $compCorporal->setGrasaCorporal($parameters['corporal']['grasa_corporal']);
                    $compCorporal->setAguaCorporal($parameters['corporal']['agua_corporal']);
                    $compCorporal->setMasaMusculo($parameters['corporal']['masa_musculo']);
                    $compCorporal->setValoracionFisica($parameters['corporal']['valoracion_fisica']);
                    $compCorporal->setEdadMetabolica($parameters['corporal']['edad_metabolica']);
                    $compCorporal->setDciBmr($parameters['corporal']['dci_bmr']);
                    $compCorporal->setMasaOsea($parameters['corporal']['masa_osea']);
                    $compCorporal->setGrasaVisceral($parameters['corporal']['grasa_visceral']);
                    $compCorporal->setFecha(new \DateTime('now'));
                    $compCorporal->setConsulta($entity);
                    $compCorporal->setEstetica($estetica);
                    //var_dump($parameters['corporal']['masa_osea']); 
                    $em->persist($compCorporal);
                    $em->flush();
                    
                    
//                  die();
                }    
                
                if(isset($parameters['botox'])){
                    $estetica = $em->getRepository('DGPlusbelleBundle:Estetica')->find($parameters['dgplusbellebundle_consulta']['estetica']);
                    
                    foreach ($parameters['botox'] as $value) {
                        //var_dump($value['area_inyectar']);
                        //die();
                        $botox = new \DGPlusbelleBundle\Entity\ConsultaBotox();

                        $botox->setAreaInyectar($value['area_inyectar']);
                        $botox->setUnidades($value['unidades']);

                        $botox->setFechaCaducidad(new \DateTime($value['caducidad']));
                        $botox->setLote($value['lote']);
                        $botox->setMarcaProducto($value['marca_producto']);
                        $botox->setNumAplicacion($value['num_aplicacion']);
                        $botox->setValor($value['valor']);
                        
                        if(isset($value['recomendaciones'])){
                            $botox->setRecomendaciones($value['recomendaciones']);
                        }
                        
                        $botox->setConsulta($entity);
                        $botox->setEstetica($estetica);
                        $em->persist($botox);
                        $em->flush();
                    }
                } 
            }
                
            if(isset($parameters['dgplusbellebundle_consulta']['sesiontratamiento'])){
                $recetaid = $parameters['dgplusbellebundle_consulta']['sesiontratamiento'];
            
                $dql = "SELECT det.id, det.nombre "
                        . "FROM DGPlusbelleBundle:DetallePlantilla det "
                        . "JOIN det.plantilla pla "
                        . "WHERE pla.id =  :plantillaid";

                $parametros2 = $em->createQuery($dql)
                            ->setParameter('plantillaid', $recetaid)
                            ->getResult();
            
                foreach($parametros2 as $p){
                    $dataReporte2 = new HistorialConsulta;
                    $detalle = $em->getRepository('DGPlusbelleBundle:DetallePlantilla')->find($p['id']);

                    $dataReporte2->setDetallePlantilla($detalle);       
                    $dataReporte2->setConsulta(null);
                    $dataReporte2->setConsultaReceta($entity);

                    $nparam = explode(" ", $p['nombre']);
                    //var_dump(count($nparam)); 
                    $lon = count($nparam);
                    if($lon > 1){
                        $pnombre = $nparam[0];
                        foreach($nparam as $key => $par){
                            //var_dump($key);
                            if($key +1 != $lon){
                                //var_dump($lon);
                                $pnombre .= '_'.$nparam[$key + 1];
                            }
                        }
                        $dataReporte2->setValorDetalle($parameters[$pnombre."2"]);
                    } else {
                        $dataReporte2->setValorDetalle($parameters[$p['nombre']."2"]);
                    }
                   //var_dump($p['nombre']); 


                    $em->persist($dataReporte2);
                    $em->flush();
                }
            }
            
            
            //$f = $gg;
            /*  if($producto){
                $this->establecerConsultaProducto($entity, $producto, $indicaciones);
            } */
            $idEmpleado = $usuario->getPersona()->getEmpleado()[0]->getId();
            $empleados=$this->verificarComision($idEmpleado,null);
            
            if($empleados[0]['suma'] >= $empleados[0]['meta'] && !$empleados[0]['comisionCompleta']){
                $this->get('envio_correo')->sendEmail($empleados[0]['email'],"","","","cumplio su objetivo");
                $empComision = $em->getRepository('DGPlusbelleBundle:Empleado')->find($empleado[0]->getId());
                $empComision->setComisionCompleta(1);
                
                $em->persist($empComision);
                $em->flush();
            }
            //$usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se registro una nueva consulta",$usuario->getId());
            
            switch($accion){
                case 'C';
                    return $this->redirect($this->generateUrl('admin_cita'));
                    break;
                case 'P';
                    return $this->redirect($this->generateUrl('admin_paciente'));
                    break;
                case 'E';
                    return $this->redirect($this->generateUrl('admin_consultas_paciente'));
                    break;
            }
            
        //}

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'flag'   => $flag,
        );
    }

    /**
     * Creates a form to create a Consulta entity.
     *
     * @param Consulta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Consulta $entity,$tipo,$identidad, \DGPlusbelleBundle\Entity\Paciente $paciente)
    {
        if($tipo == 1){
            $form = $this->createForm(new ConsultaType(), $entity, array(
                'action' => $this->generateUrl('admin_consulta_create', array('id' => 1,'identidad'=>$identidad, 'idpac'=>$paciente->getId())),
                'method' => 'POST',
            ));
            $form->add('paciente','entity', array( 'label' => 'Paciente','required'=>false,
                         'empty_value'   => 'Seleccione un paciente...',
                         'class'         => 'DGPlusbelleBundle:Paciente',
                         'attr'=>array(
                         'class'=>'form-control input-sm pacienteConsulta'
                         )
                       ));
        }
        elseif($tipo == 2) {
            $form = $this->createForm(new ConsultaConPacienteType(), $entity, array(
                'action' => $this->generateUrl('admin_consulta_create', array('id' => 2,'identidad'=>$identidad, $paciente, 'idpac'=>$paciente->getId())),
                'method' => 'POST',
            ));
            //$paciente = $entity->getPaciente();
            //var_dump($entity);
            $form->add('paciente', 'entity', array(
                        'label'         =>  'Paciente',
                        //'empty_value'=>'Seleccione una actividad',
                        'class'         =>  'DGPlusbelleBundle:Paciente',
                        'query_builder' =>  function(EntityRepository $repositorio) use (  $paciente ){
                    return $repositorio
                            ->createQueryBuilder('pa')
                            ->where('pa.id = :pac')
                            ->setParameter(':pac', $paciente->getId());
                    }));
            
            $form->add('sesiontratamiento', 'entity', array('required'=>false,
                    'label'         =>  'Nombre',
                    'empty_value'=>'Seleccione una opcion',
                    'class'         =>  'DGPlusbelleBundle:Plantilla',
                    'query_builder' => function(EntityRepository $repository) {
                return $repository->obtenerRecetasActivo();
            },
                    'mapped' => false
                ));        
                    
            $form->add('registraReceta', 'choice', array(
                    'label'=> 'Receta',
                    'choices'  => array('1' => 'Sí', '0' => 'No'),
                    'multiple' => false,
                    'expanded'=>'true'
                   
                 
                ));
            
            $form->add('reportePlantilla', 'choice', array(
                    'label'=> 'Registro clínico',
                    'choices'  => array('1' => 'Sí', '0' => 'No'),
                    'multiple' => false,
                    'expanded'=>'true'
                   
                 
                ));
            
            $form->add('plantilla', 'entity', array('required'=>false,
                    'label'         =>  'Historias clínicas',
                    'empty_value'=>'Seleccione una opcion',
                    'class'         =>  'DGPlusbelleBundle:Plantilla',
                    'query_builder' => function(EntityRepository $repository) {
                    return $repository->otrosDocActivo();
                },
                    'mapped' => false
                ));
        }
        else {
            $form = $this->createForm(new ConsultaConPacienteType(), $entity, array(
                'action' => $this->generateUrl('admin_consulta_create', array('id' => 3,'identidad'=>$identidad, 'idpac'=>-1)),
                'method' => 'POST',
            ));

//            $form->add('paciente', 'choice', array(
//                        'label'         =>  'Paciente',
//                        'empty_value'=>'Seleccione un paciente',
//                        'choices'   => array()
//                        ));
            
            $form->add('reportePlantilla', 'choice', array(
                    'label'=> 'Registro clínico',
                    'choices'  => array('1' => 'Sí', '0' => 'No'),
                    'multiple' => false,
                    'expanded'=>'true'
                   
                 
                ));
            
            $form->add('estetica', 'entity', array('required'=>false,
                    'label'         =>  'Tipo de consulta de estetica',
                    'empty_value'=>'Seleccione una opcion',
                    'class'         =>  'DGPlusbelleBundle:Estetica',
//                    'query_builder' => function(EntityRepository $repository) {
//                    return $repository->otrosDocActivo();
//                },
                    'mapped' => false
                ));
        }
        
        $form->add('submit', 'submit', array('label' => 'Guardar',
                                             'attr'  => array(
                                                     'class'=>'btn btn-success btn-sm'
                                                    )
                                            ));

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
        //Recuperación del paciente
        $request = $this->getRequest();
        $identidad= $request->get('identidad');
        $form   = $this->createCreateForm($entity,1,$identidad);

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
    public function newconpacienteAction()
    {
        //Metodo para consulta nueva con id de paciente
        $entity = new Consulta();
        
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del paciente
        $request = $this->getRequest();
        $cadena= $request->get('id');
        $flag = 0;
        
        if($cadena != NULL) {
            //Obtener el id del parametro
            $idEntidad = substr($cadena, 1);

            //$identidad= $request->get('identidad');
            //Busqueda del paciente
            $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idEntidad);

            //Seteo del paciente en la entidad
            $entity->setPaciente($paciente);
            //var_dump($paciente);
            $form   = $this->createCreateForm($entity,2,$cadena, $paciente);
            $entity->setPaciente($paciente);
        } else {
            $paciente = new \DGPlusbelleBundle\Entity\Paciente();
            $form   = $this->createCreateForm($entity, 3, $cadena, $paciente);
            $flag = 1;
        }    
            
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'flag'   => $flag,
        );
            
    }
    
    
    /**
     * Displays a form to create a new Consulta entity.
     *v
     * @Route("/newconcita", name="admin_consulta_nueva_cita")
     * @Method("GET")
     * @Template("DGPlusbelleBundle:Consulta:newconpaciente.html.twig")
     */
    public function newconcitaAction()
    {
        //Metodo para consulta nueva con el id de cita
        $entity = new Consulta();
        
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del id
        $request = $this->getRequest();
        $cadena= $request->get('id');
        //$identidad= $request->get('identidad');
        //Obtener el id del parametro
        $idEntidad = substr($cadena, 1);
        $cita = $em->getRepository('DGPlusbelleBundle:Cita')->find($idEntidad);
        //var_dump($cadena);
        //var_dump($cita);
        $tratamiento = $cita->getTratamiento();
        
        $entity->setTratamiento($tratamiento);
        $idpaciente=$cita->getPaciente()->getId();
        
        //Busqueda del paciente
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idpaciente);
        //Seteo del paciente en la entidad
        $entity->setPaciente($paciente);
        
        $form   = $this->createCreateForm($entity,2,$cadena);

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
    public function editconpacienteAction($id)
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
        
        $dql = "SELECT hc, con, det, pla "
                    . "FROM DGPlusbelleBundle:HistorialConsulta hc "
                    . "JOIN hc.consulta con "
                    . "JOIN hc.detallePlantilla det "
                    . "JOIN det.plantilla pla "
                    . "WHERE hc.consulta =  :idconsulta";
            
        $plantilla = $em->createQuery($dql)
                    ->setParameter('idconsulta', $id)
                    ->getResult();
            
            //var_dump($plantilla[0]);
        //$plantilla = "";
        
        return array(
            'entity'      => $entity,
            'plantilla'      => $plantilla,
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
        $parameters = $request->request->all();
        
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
                   
                // remove the relationship between the tag and the Task 
                    foreach ($originalTags as $tag) { 
                
                         // if you wanted to delete the Tag entirely, you can also do that 
                        $em->remove($tag);
                        //$em->persist($tag);          
            }
            
            $em->flush();
           
             $plantillaid = $parameters['dgplusbellebundle_consulta']['plantilla'];
             //var_dump($plantillaid);
             
            $dql = "SELECT det.id, det.nombre "
                    . "FROM DGPlusbelleBundle:DetallePlantilla det "
                    . "JOIN det.plantilla pla "
                    . "WHERE pla.id =  :plantillaid";
            
            $parametros = $em->createQuery($dql)
                        ->setParameter('plantillaid', $plantillaid)
                        ->getResult();
            
            $query = $em->createQuery(
                            'SELECT hc
                               FROM DGPlusbelleBundle:HistorialConsulta hc
                               JOIN hc.consulta c
                              WHERE c.id = :consulta
                           ORDER BY c.id ASC'
                        )->setParameter('consulta', $id);

            $detalleP = $query->getResult();
            
            foreach($detalleP as $key1 => $det){
                $historialConsulta = $em->getRepository('DGPlusbelleBundle:HistorialConsulta')->find($det->getId());
                
                if (!$historialConsulta) {
                    throw $this->createNotFoundException('Unable to find HistorialConsulta entity.');
                }
                
                $em->remove($historialConsulta);
                $em->flush();
            }
           
                
            //$o = $e;
            foreach($parametros as $key => $p){
                
                $dataReporte = new HistorialConsulta;
                $detalle = $em->getRepository('DGPlusbelleBundle:DetallePlantilla')->find($p['id']);
                
                $dataReporte->setDetallePlantilla($detalle);       
                $dataReporte->setConsulta($entity);
                
                $nparam = explode(" ", $p['nombre']);
                //var_dump(count($nparam)); 
                $lon = count($nparam);
                if($lon > 1){
                    $pnombre = $nparam[0];
                    foreach($nparam as $key => $par){
                        //var_dump($key);
                        if($key +1 != $lon){
                            //var_dump($lon);
                            $pnombre .= '_'.$nparam[$key + 1];
                        }    
                    }
                    $dataReporte->setValorDetalle($parameters[$pnombre]);
                } else {
                    $dataReporte->setValorDetalle($parameters[$p['nombre']]);
                }
                
                
                
                //$dataReporte->setValorDetalle($parameters[$p['nombre']]);
                
                $em->persist($dataReporte);
                $em->flush();
            }
            
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se actualizo una consulta",$usuario->getId());
            
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
     * @Route("/{id}/borrar/{idpaciente}", name="admin_consulta_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id,$idpaciente)
    {
        //$form = $this->createDeleteForm($id);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);
//
//            if (!$entity) {
//                throw $this->createNotFoundException('Unable to find Consulta entity.');
//            }
//
//            $em->remove($entity);
//            $em->flush();
//        }
        $idpaciente="P".$idpaciente;
        //var_dump($idpaciente);
//        $form = $this->createDeleteForm($id);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Consulta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Consulta entity.');
            }

            $em->remove($entity);
            $em->flush();
//        }

//        return $this->redirect($this->generateUrl('admin_consulta'));
        return $this->redirect($this->generateUrl('admin_historial_consulta',array('id'=>$idpaciente)));
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
    public function historialconsultaAction(){
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del id
        $request = $this->getRequest();
        $idPacient= $request->get('id');  

        $idPaciente=  substr($idPacient, 1);
        $consultas = $em->getRepository('DGPlusbelleBundle:Consulta')->findBy(array('paciente'=>$idPaciente));
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
        
        //$idPaciente = $entity->getPaciente()->getId();
        
        //$dias = $em->createQuery($dql)
                //->setParameter('idEmp', $idEmp)
                //->getArrayResult();
       
        /*$stmt = $this->getDoctrine()->getEntityManager()
            ->getConnection()
             ->prepare('select paquete.id, paquete.nombre, paquete_tratamiento.num_sesiones from paciente natural join persona natural join venta_paquete natural join paquete natural join paquete_tratamiento where paciente.id = 1');
        $stmt->execute();
        $result = $stmt->fetchAll();
        */
        //echo $idPaciente;
//        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        $CompraPaciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($idPaciente);
        $paciente = $CompraPaciente;
        $paquetes = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->findBy(array('paciente' => $CompraPaciente->getPersona()->getId()));

        $tratamientos= $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->findBy(array('paciente' => $CompraPaciente->getPersona()->getId()));
        
        $dql = "SELECT count(vp) FROM DGPlusbelleBundle:VentaPaquete vp"
               . " WHERE vp.paciente=:paciente";
        $totalPaquetes = $em->createQuery($dql)
                ->setParameter('paciente', $CompraPaciente->getPersona())
                ->getArrayResult();
        
        $dql = "SELECT count(c) FROM DGPlusbelleBundle:PersonaTratamiento c"
               . " WHERE c.paciente=:paciente";
        $totalTratamientos = $em->createQuery($dql)
                ->setParameter('paciente', $CompraPaciente->getPersona()->getId())
                ->getArrayResult();
        
        $empleados=$this->verificarComision(null,null);
        
        //$seguimientopaquete = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findBy(array('idVentaPaquete' => 30));
        $regnoeditpaquete = array();
        foreach($paquetes as $row){
            $c=0;
            $seguimientopaquete = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findBy(array('idVentaPaquete' => $row->getId()));
            foreach($seguimientopaquete as $reg){
                if($reg->getnumSesion()!=0){
                    $c++;
                }
                
                //array_push($regnoedit, array('idpaquete'=>$row->getId()));                
            }
            
            if($c!=0){
                    array_push($regnoeditpaquete, array('idpaquete'=>$row->getId()));
            }
            
        }
        $rsm2 = new ResultSetMapping();
        
        $sql2 = "SELECT vp.id paquete, cast(sum(abo.monto) as decimal(36,2)) abonos "
                . "FROM abono abo RIGHT OUTER JOIN venta_paquete vp on abo.venta_paquete = vp.id "
                . "WHERE vp.paciente=:paciente "
                . "GROUP BY vp.id";
        
        $rsm2->addScalarResult('paquete','paquete');
        //$rsm2->addScalarResult('abono','abono');
        $rsm2->addScalarResult('abonos','abonos');

        $abonosPaq = $em->createNativeQuery($sql2, $rsm2)
                ->setParameter('paciente', $CompraPaciente->getPersona()->getId())
                ->getResult();

        $rsm3 = new ResultSetMapping();
        
        $sql3 = "select p.id tratamiento, cast(sum(abo.monto) as decimal(36,2)) abonos "
                . "FROM abono abo RIGHT OUTER JOIN persona_tratamiento p on abo.persona_tratamiento = p.id "
                . "WHERE p.paciente=:paciente "
                . "GROUP BY p.id";

        $rsm3->addScalarResult('tratamiento','tratamiento');
        $rsm3->addScalarResult('abonos','abonos');

        $abonosTrata = $em->createNativeQuery($sql3, $rsm3)
                ->setParameter('paciente', $CompraPaciente->getPersona()->getId())
                ->getResult();
        //$seguimientotratamiento= $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->findBy(array('idVentaPaquete' => 30));

        $regnoedittratamiento = array();
        foreach($tratamientos as $row){
            $c=0;
            $seguimientopaquete = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findBy(array('idPersonaTratamiento' => $row->getId()));
            foreach($seguimientopaquete as $reg){
                if($reg->getnumSesion()!=0){
                    $c++;
                }
                
                //array_push($regnoedit, array('idpaquete'=>$row->getId()));
            }
            
            if($c!=0){
                    array_push($regnoedittratamiento, array('idpersonatrat'=>$row->getId()));
            }            
        }
        
//      sendEmail($to, $cc, $bcc,$replay, $body){
//      $comision;
//      Formato: dd-mm-yy
//      echo calcular_edad(“01-10-1989?); // Resultado: 21
        
        return array(
            //'entities' => $entities,
            //'entity' => $entity,
            'totalTratamientos'=> $totalTratamientos[0][1],//grafica de estadisticas
            'totalPaquetes'=> $totalPaquetes[0][1],//grafica de estadisticas
            'totalConsultas'=> count($consultas),//grafica de estadisticas
            'edad' => $edad,
            'consultas' => $consultas,
            'paquetes' => $paquetes,
            'abonosPaq' => $abonosPaq,
            'empleados' => $empleados,
            'tratamientos' => $tratamientos,
            'abonosTrata' => $abonosTrata,
            'paciente' => $paciente,
            'expediente'=>$expnum,
            'paquetesnoedit'=>$regnoeditpaquete,
            'tratamientosnoedit'=>$regnoedittratamiento,
            'idPaciente'=>$idPacient
            );
    }
    
    
    function verificarComision($id,$fecha){
        $em = $this->getDoctrine()->getManager();
        if($id!=null){//Un empleado especifico
            $dql = "SELECT emp.id, emp.meta, emp.foto, p.nombres, p.apellidos,emp.comisionCompleta,p.email,emp.porcentaje as por "
                    . "FROM DGPlusbelleBundle:Empleado emp "
                    . "JOIN emp.persona p "
                    . "WHERE emp.estado=true "
                    . "AND emp.id =:idEmpleado";
            $empleados= $em->createQuery($dql)
                       ->setParameter('idEmpleado',$id)
                       ->getResult();
                       //var_dump($empleados);
     
        }
        else{//Todos los empleados
            $dql = "SELECT emp.id, emp.meta, emp.foto, p.nombres, p.apellidos,emp.comisionCompleta,p.email,emp.porcentaje as por  "
                    . "FROM DGPlusbelleBundle:Empleado emp "
                    . "JOIN emp.persona p "
                    . "WHERE emp.estado=true ";
            $empleados= $em->createQuery($dql)
                        ->getResult();
        }
            //$empleados= $em->getRepository('DGPlusbelleBundle:Empleado')->findBy(array('estado'=>true));
        if($fecha==null){
            $fecha= date('Y-m');
        }

            /*if($fecha<10){
                $fecha = "0".$fecha;
            }*/
            //$mes = '02';
            foreach($empleados as $key=>$empleado){
                $dql = "SELECT sum(p.costo)"
                    . " FROM"
                    . " DGPlusbelleBundle:VentaPaquete vp"
                    . " JOIN vp.paquete p"
                    . " JOIN vp.empleado emp"
                    . " JOIN emp.empleado em"
                    . " WHERE vp.fechaVenta LIKE :mes AND em.estado=true AND em.id=:idEmpleado";
                
                $ventaspaquete = $em->createQuery($dql)
                       ->setParameters(array('mes'=>$fecha.'___','idEmpleado'=>$empleado['id']))
                       ->getResult();
                
                $dql = "SELECT sum(vp.costoConsulta)"
                    . " FROM"
                    . " DGPlusbelleBundle:PersonaTratamiento vp"
                    . " JOIN vp.tratamiento t"
                    . " JOIN vp.empleado emp"
                    . " JOIN emp.empleado em"
                    . " WHERE vp.fechaVenta LIKE :mes AND em.estado=true AND em.id=:idEmpleado";
                $ventastratamientos = $em->createQuery($dql)
                       ->setParameters(array('mes'=>$fecha.'___','idEmpleado'=>$empleado['id']))
                       ->getResult();

                //var_dump($comision);
                $empleados[$key]['suma']= $ventaspaquete[0][1] + $ventastratamientos[0][1];
                $porcentaje = 0;
                if($empleados[$key]['suma']!=null){
                    if($empleados[$key]['meta']>=$empleados[$key]['suma'] ){
                        $porcentaje = ($empleados[$key]['suma']/$empleados[$key]['meta'])*100;
                        $empleados[$key]['bono']="No";
                    }
                    else{
                        if($empleados[$key]['meta']<$empleados[$key]['suma']){
                            $empleados[$key]['bono']="Si";
                            $porcentaje = 100; 
                        }
                    }
                    $empleados[$key]['comision'] = ($empleados[$key]['suma']*$empleados[$key]['por'])/100;
                }
                else{
                    $empleados[$key]['comision'] = "";
                    $empleados[$key]['bono']="No";
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
            //$usuario= $this->get('security.token_storage')->getToken()->getUser();
            //var_dump($usuario->getPersona()->getId());
            //var_dump($empleados);
            /**/
        
        return $empleados;
    }
    
   /**
    * Ajax utilizado para buscar los parametros del reporte de una plantilla
    *  
    * @Route("/buscarParametrosPlantilla", name="admin_busqueda_parametros")
    */
    public function buscarParametrosPlantillaAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $plantillaid = $this->get('request')->request->get('id');
             
            $em = $this->getDoctrine()->getManager();            
            $dql = "SELECT det.id, det.nombre,det.tipoParametro "
                    . "FROM DGPlusbelleBundle:DetallePlantilla det "
                    . "JOIN det.plantilla pla "
                    . "WHERE pla.id =  :plantillaid";
            
            $parametros = $em->createQuery($dql)
                        ->setParameter('plantillaid', $plantillaid)
                        ->getResult();
            
            $response = new JsonResponse();
            $response->setData(array(
                                'query' => $parametros
                            )); 
            
            return $response; 
        } else {    
            return new Response('0');              
        }  
    }
    
    
    /**
    * Ajax utilizado para buscar los parametros del reporte de una consulta de estetica
    *  
    * @Route("/buscarParametrosEstetica", name="admin_busqueda_parametros_estetica")
    */
    public function buscarParametrosEsteticaAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $esteticaid = $this->get('request')->request->get('id');
            $em = $this->getDoctrine()->getManager();            
                
            if($esteticaid != 3){
                $dql = "SELECT est.id estetica, dest.id, dest.nombre "
                        . "FROM DGPlusbelleBundle:DetalleEstetica dest "
                        . "JOIN dest.estetica est "
                        . "WHERE est.id =  :esteticaid";

                $parametros = $em->createQuery($dql)
                            ->setParameter('esteticaid', $esteticaid)
                            ->getResult();

                $response = new JsonResponse();
                $response->setData(array(
                                    'query' => $parametros
                                )); 

                return $response; 
            } else {
                $dql = "SELECT est.id estetica "
                        . "FROM DGPlusbelleBundle:Estetica est "
                        . "WHERE est.id =  :esteticaid";

                $parametros = $em->createQuery($dql)
                            ->setParameter('esteticaid', $esteticaid)
                            ->getResult();

                $response = new JsonResponse();
                $response->setData(array(
                                    'query' => $parametros
                                )); 

                return $response; 
            }    
        } else {    
            return new Response('0');              
        }  
    }
    
    
    /**
    * Ajax utilizado para buscar los parametros del reporte de una plantilla
    *  
    * @Route("/buscaropcionesParametrosPlantilla", name="admin_busqueda_opciones_parametros")
    */
    public function buscarpcionesParametrosPlantillaAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $id = $this->get('request')->request->get('id');
             
            $em = $this->getDoctrine()->getManager();            
            $dql = "SELECT opcdet.nombre,det.id "
                    . "FROM DGPlusbelleBundle:DetalleOpcionesPlantilla opcdet "
                    . "JOIN opcdet.detallePlantilla det "
                    . "WHERE opcdet.detallePlantilla =  :id";
            
            $parametros = $em->createQuery($dql)
                        ->setParameter('id', $id)
                        ->getResult();
            
            $response = new JsonResponse();
            $response->setData(array(
                                'query' => $parametros
                            )); 
            
            return $response; 
        } else {    
            return new Response('0');              
        }  
    }
    
    
    /**
    * Ajax utilizado para buscar los parametros del reporte de una plantilla de estetica
    *  
    * @Route("/buscaropcionesParametrosEstetica", name="admin_busqueda_opciones_estetica")
    */
    public function buscarpcionesParametrosEsteticaAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $id = $this->get('request')->request->get('id');
             
            $em = $this->getDoctrine()->getManager();            
            $dql = "SELECT opcdet.id opcion, opcdet.nombre, det.id "
                    . "FROM DGPlusbelleBundle:OpcionesDetalleEstetica opcdet "
                    . "JOIN opcdet.detalleEstetica det "
                    . "WHERE opcdet.detalleEstetica =  :id";
            
            $parametros = $em->createQuery($dql)
                        ->setParameter('id', $id)
                        ->getResult();
            
            $response = new JsonResponse();
            $response->setData(array(
                                'query' => $parametros
                            )); 
            
            return $response; 
        } else {    
            return new Response('0');              
        }  
    }
    
    
    /**
     * Lista todos los empleados con su respectivo progreso de ventas
     *
     * @Route("/progresoempleadoventa/", name="admin_empleado_progreso_venta")
     * @Method("GET")
     * @Template()
     */
    public function progresoventaAction(){
        //$fecha= $request->get('fecha');
        //$empleados=$this->verificarComision(null,$fecha);
        return array(
            //'empleados' => $empleados,
            //'fecha' => $fecha,
        );
    }
    
    
    /**
    * Ajax utilizado para buscar los antecedentes en ventas del empleado
    *  
    * @Route("/progresoempleadoventaajax/", name="admin_empleado_progreso_venta_ajax")
    * @Method("GET")
    * @Template("DGPlusbelleBundle:Consulta:progresoventaajax.html.twig")
    */
    public function progresoventaajaxAction(Request $request)
    {
        $fecha= $request->get('fecha');
        
        $time = new \DateTime('01-'.$fecha);
        
        $timestamp = $time->getTimestamp(); // Unix timestamp
        
        //var_dump($timestamp);
        
        $fecha = $time->format('Y-m'); // 2003-10-16
        
        //var_dump($time);
        //$fecha = date('Y-m',$time);
        //var_dump($fecha);
        $empleados=$this->verificarComision(null,$fecha);
        
        //var_dump($empleados);
        
        return array(
            'empleados' => $empleados,
            'fecha' => $fecha,
        );
    }
    
    
    
    
    
    
    /**
     * Metodo que sirve para generar el expediente del paciente
     *
     * @param \DGPlusbelleBundle\Entity\Paciente $paciente
     *
     */
    private function generarExpediente($paciente)
    {
        $em = $this->getDoctrine()->getManager();
        
        $expediente = new Expediente();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        // Obtencion del apellidos  y nombres del paciente
        $apellido = $paciente->getPersona()->getApellidos();
        $nombre = $paciente->getPersona()->getNombres();

        
        $search  = array('Á', 'É', 'Í', 'Ó', 'Ú');
        $replace = array('A', 'E', 'I', 'O', 'U');
        
        $apellido = str_replace($search,$replace , $apellido);
        $nombre = str_replace($search,$replace , $nombre);
        
        //Generacion del numero de expediente
//        $numeroExp = $nombre[0].$apellido[0].date("Y");
        $numeroExp = substr(strtoupper($nombre), 0,1).substr(strtoupper($apellido), 0,1).date("Y");
//        $numeroExp = strtoupper ($numeroExp);
//        echo $numeroExp;
                
        $dql = "SELECT COUNT(exp)+1 FROM DGPlusbelleBundle:Expediente exp WHERE exp.numero LIKE :numero";

        $num = $em->createQuery($dql)
                   ->setParameter('numero','%'.$numeroExp.'%')
                   ->getResult();
        //var_dump($user);
        $numString = $num[0]["1"];
        //var_dump($numString);

        switch(strlen($numString)){
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
//        var_dump($numeroExp);
//        die();
        //Seteo de valores del expediente
        $expediente->setFechaCreacion(new \DateTime('now'));
        $expediente->setHoraCreacion(new \DateTime('now'));
        $expediente->setEstado(true);
        $expediente->setNumero($numeroExp);
        $expediente->setPaciente($paciente);
        $expediente->setUsuario($user);

//        $paciente->setExpediente($expediente);
        $em->persist($expediente);
        $em->flush();
        return $numeroExp;
    }
    
    
    
}
