<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Abono;
use DGPlusbelleBundle\Entity\Paciente;
use DGPlusbelleBundle\Form\AbonoType;
use Doctrine\ORM\EntityRepository;

/**
 * Abono controller.
 *
 * @Route("/admin/abono")
 */
class AbonoController extends Controller
{

    /**
     * Lists all Abono entities.
     *
     * @Route("/", name="admin_abono")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Abono')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Abono entity.
     *
     * @Route("/", name="admin_abono_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Abono:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Abono();
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $idpaquetes = $request->get('paquete');
        $idtratamientos = $request->get('tratamiento');
        $paciente = $request->get('paciente');
        
        $pac = $em->getRepository('DGPlusbelleBundle:Paciente')->findOneBy(array('persona'=>$paciente));
        //var_dump($pac);
        $form   = $this->createCreateForm($entity, $paciente, $idpaquetes, $idtratamientos);
       // $form   = $this->createCreateForm($entity, $paciente, $idtratamientos);
        //$form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $parameters = $request->request->all();
        //var_dump($parameters);
        $entity->setFechaAbono(new \DateTime('now'));
        //var_dump($idpaquetes);
        $vPaquete=array();
        $vTratamiento=array();
        
        $aux = explode("-", $parameters['dgplusbellebundle_abono']['submit']);
        
        if($aux[1]==0){
            //var_dump($aux[0]);
            $entity->setPersonaTratamiento(null);
            $vPaquete = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($aux[0]);
            $entity->setVentaPaquete($vPaquete);
        }
        else{
            //var_dump($aux[0]);
            $entity->setVentaPaquete(null); 
            $vTratamiento = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->find($aux[0]);
            $entity->setPersonaTratamiento($vTratamiento);
            //var_dump($vTratamiento);
        }
        $entity->setPaciente($pac);

        
        //die();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se registro un abono correctamente",$usuario->getId());
            
            //return $this->redirect($this->generateUrl('admin_abono', array('id' => $entity->getId())));
            return $this->redirect($this->generateUrl('admin_paciente', array('id' => $pac->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Abono entity.
     *
     * @param Abono $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Abono $entity, $paciente, $paquete, $tratamiento)
    {
       // var_dump($paquete);
        $form = $this->createForm(new AbonoType(), $entity, array(
            'action' => $this->generateUrl('admin_abono_create', array('paciente' => $paciente, 'paquete' => $paquete, 'tratamiento' => $tratamiento)),
            'method' => 'POST',
        ));

        
        
        $form->add('ventaPaquete', 'entity', 
                  array( 'label'         => 'Paquete','required'=>false,
                         'empty_value'   => 'Seleccione un paquete...',
                         'class'         => 'DGPlusbelleBundle:VentaPaquete',
                         'query_builder' => function(EntityRepository $r) use ( $paciente, $paquete ){
                                                return $r->createQueryBuilder('s')
                                                         ->innerJoin('s.paquete', 'p')
                                                         ->innerJoin('s.paciente', 'pac')
                                                        ->where('s.cuotas > 0')
                                                        ->andWhere('pac.id = :idpac')
                                                        ->andWhere('s.id IN (:paquetes)')
                                                        ->setParameter(':idpac', $paciente)
                                                        ->setParameter('paquetes', $paquete)
                                                    ;   
                                            } ,
                         'attr'=>array(
                         'class'=>'form-control input-sm paqueteAbono'
                         )
                       ));
                                            
         $form->add('personaTratamiento', 'entity', 
                  array( 'label'         => 'Tratamiento','required'=>false,
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:PersonaTratamiento',
                         'query_builder' => function(EntityRepository $r) use ( $paciente, $tratamiento ){
                                                return $r->createQueryBuilder('s')
                                                         ->innerJoin('s.tratamiento', 'p')
                                                        ->where('s.cuotas > 0')
                                                        ->andWhere('s.id IN (:tratamientos)')
                                                        ->setParameter('tratamientos', $tratamiento)
                                                    ;   
                                            } ,
                         'attr'=>array(
                         'class'=>'form-control input-sm tratamientoAbono'
                         )
                       ));                                    
        
        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Abono entity.
     *
     * @Route("/new", name="admin_abono_new", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Abono();
         $em = $this->getDoctrine()->getManager();
        
        //Recuperación del paciente
        $request = $this->getRequest();
        $id= $request->get('id');
        //var_dump($id);
        $id = substr($id, 1);
        
        //Busqueda del paciente
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        //Seteo del paciente en la entidad
       // $persona=$paciente->getPersona();
        $paquetes = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->findBy(array('paciente'=>$paciente->getPersona()->getId()));
        $tratamientos = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->findBy(array('paciente'=>$paciente->getPersona()->getId()));
        //var_dump($paquetes);
        $idpaquetes=array();
        $idtratamientos=array();
        foreach ($paquetes as $paq){
            $idpaq=$paq->getId();
            $cuota=$paq->getCuotas();
            $dql = "SELECT count(ab) FROM DGPlusbelleBundle:Abono ab "
               
                . "WHERE ab.ventaPaquete=:id ";
                   
            $abonos=array();
            
            $abonos = $em->createQuery($dql)
                       ->setParameters(array('id'=>$idpaq))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            //var_dump($abonos[0]);
            if($abonos[0][1]<$cuota){
                array_push($idpaquetes, $idpaq); 
            }
        }
        
        foreach ($tratamientos as $trat){
            $idtrat=$trat->getId();
            $cuota=$trat->getCuotas();
           // var_dump($cuota);
            $dql = "SELECT count(tt) FROM DGPlusbelleBundle:Abono tt "
               
                . "WHERE tt.personaTratamiento=:id and tt.paciente=:idpaciente";
                   
            $abonos=array();
            
            $abonos = $em->createQuery($dql)
                       ->setParameters(array('id'=>$idtrat,'idpaciente'=>$paciente->getPersona()->getId()))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
          // var_dump($abonos[0]);
            if($abonos[0][1]<$cuota){
                array_push($idtratamientos, $idtrat); 
            }
        }
        
        //var_dump($idpaquetes);  
        //var_dump($idpaquetes);
        $entity->setPaciente($paciente);
        
        $form   = $this->createCreateForm($entity, $paciente->getPersona()->getId(), $idpaquetes, $idtratamientos);
        //$form   = $this->createCreateForm($entity, $paciente->getPersona()->getId(), $idtratamientos);
        
        return array(
            'entity'   => $entity,
            'paciente' => $paciente,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Abono entity.
     *
     * @Route("/{id}", name="admin_abono_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Abono')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Abono entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Abono entity.
     *
     * @Route("/{id}/edit", name="admin_abono_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Abono')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Abono entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Abono entity.
    *
    * @param Abono $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Abono $entity)
    {
        $form = $this->createForm(new AbonoType(), $entity, array(
            'action' => $this->generateUrl('admin_abono_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Abono entity.
     *
     * @Route("/{id}", name="admin_abono_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Abono:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Abono')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Abono entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_abono_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Abono entity.
     *
     * @Route("/{id}", name="admin_abono_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Abono')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Abono entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_abono'));
    }

    /**
     * Creates a form to delete a Abono entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_abono_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    
    
    
    
    
    
    /**
     * 
     *
     * @Route("/abono/data/listado", name="admin_abonos_paciente_data")
     */
    public function dataAbonoAction(Request $request)
    {
           

        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        $id = $request->query->get('id');
        //var_dump($id);
        $em = $this->getDoctrine()->getEntityManager();
        $expedientesTotal = $em->getRepository('DGPlusbelleBundle:Abono')->findBy(array('ventaPaquete'=>$id));
        
        $paciente['draw']=$draw++;  
        $paciente['recordsTotal'] = count($expedientesTotal);
        $paciente['recordsFiltered']= count($expedientesTotal);
        $paciente['data']= array();
        
        $arrayFiltro = explode(' ',$busqueda['value']);
        
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){
            
                    
//                    $dql = "SELECT exp.numero as expediente, inc.id as id,CONCAT(CONCAT(per.nombres,' '), per.apellidos) as nombres, DATE_FORMAT(inc.fechaInicial,'%d-%m-%Y') as fechaInicial, DATE_FORMAT(inc.fechaFinal,'%d-%m-%Y') as fechaFinal,inc.notas, concat(concat('<a id=\"',inc.id),'\"><i style=\"cursor:pointer;color:#000\" data-toggle=\"tooltip\" data-original-title=\"Atrás\" class=\"infoIncapacidad fa fa-list-alt\"></i></a>','<a style=\"margin-left:5px;\" id=\"',inc.id,'\"><i style=\"cursor:pointer;color:#000\" data-toggle=\"tooltip\" data-original-title=\"Atrás\" class=\"eliminarIncapacidad fa fa-times\"></i></a>')  as link FROM DGPlusbelleBundle:Incapacidad inc "
//                        . "JOIN inc.paciente pac "
//                        . "JOIN pac.persona per "
//                        . "JOIN pac.expediente exp "
//                        . "WHERE CONCAT(upper(per.nombres),upper(per.apellidos)) LIKE upper(:busqueda) "
//                        . "ORDER BY per.nombres ASC ";
//                    
//                    $paciente['data'] = $em->createQuery($dql)
//                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
//                            ->getResult();
//                    
//                    $paciente['recordsFiltered']= count($paciente['data']);
//                    
//                    $dql = "SELECT exp.numero as expediente, inc.id as id,CONCAT(CONCAT(per.nombres,' '), per.apellidos) as nombres, DATE_FORMAT(inc.fechaInicial,'%d-%m-%Y') as fechaInicial, DATE_FORMAT(inc.fechaFinal,'%d-%m-%Y') as fechaFinal,inc.notas, concat(concat('<a id=\"',inc.id),'\"><i style=\"cursor:pointer;color:#000\" data-toggle=\"tooltip\" data-original-title=\"Atrás\" class=\"infoIncapacidad fa fa-list-alt\"></i></a>','<a style=\"margin-left:5px;\" id=\"',inc.id,'\"><i style=\"cursor:pointer;color:#000\" data-toggle=\"tooltip\" data-original-title=\"Atrás\" class=\"eliminarIncapacidad fa fa-times\"></i></a>')  as link FROM DGPlusbelleBundle:Incapacidad inc "
//                        . "JOIN inc.paciente pac "
//                        . "JOIN pac.persona per "
//                        . "JOIN pac.expediente exp "
//                        . "WHERE CONCAT(upper(per.nombres),upper(per.apellidos)) LIKE upper(:busqueda) "
//                        . "ORDER BY per.nombres ASC ";
//                    
//                    $paciente['data'] = $em->createQuery($dql)
//                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
//                            ->setFirstResult($start)
//                            ->setMaxResults($longitud)
//                            ->getResult();
        }
        else{
//            $dql = "SELECT exp.numero as expediente, pac.id as id,CONCAT(per.nombres, per.apellidos) as nombres, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link FROM DGPlusbelleBundle:Incapacidad inc "
            $dql = "SELECT suc.nombre as sucursal, DATE_FORMAT(ab.fechaAbono,'%d-%m-%Y %H:%i') as fechaAbono, CONCAT(per.nombres,' ', per.apellidos) as empleado, concat('<div class=\"pull-right\">',ab.monto,'</div>') as monto FROM DGPlusbelleBundle:Abono ab "
                    . "JOIN ab.empleado emp "
                    . "JOIN emp.persona per "
                    . "JOIN ab.sucursal suc "
                    . "WHERE ab.ventaPaquete=:id "
                    . "ORDER BY ab.fechaAbono DESC ";
            $paciente['data'] = $em->createQuery($dql)
                    ->setParameter('id',$id)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        //$longitud = $request->query->get('length');
        //var_dump($start);
        
        //var_dump(count($pacientesTotal));
        
        //$array = array("draw"=>23);
//        $paciente['draw']=23;
//        $paciente['recordsTotal']=57;
//        $paciente['recordsFiltered']=57;
        
        
        return new Response(json_encode($paciente));
    }
    
    
    
    
    
}
