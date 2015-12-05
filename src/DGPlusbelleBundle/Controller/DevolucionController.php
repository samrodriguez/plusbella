<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Devolucion;
use DGPlusbelleBundle\Form\DevolucionType;
use Doctrine\ORM\EntityRepository;

/**
 * Devolucion controller.
 *
 * @Route("/admin/devolucion")
 */
class DevolucionController extends Controller
{

    /**
     * Lists all Devolucion entities.
     *
     * @Route("/", name="admin_devolucion")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entity = new Devolucion();
        //$form = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:Devolucion')->findAll();

        return array(
            'entities' => $entities,
            'entity' => $entity,
            //'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Devolucion entity.
     *
     * @Route("/", name="admin_devolucion_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Devolucion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Devolucion();
        
        $idpaquetes = $request->get('paquete');
        $idtratamientos = $request->get('tratamiento');
        $paciente = $request->get('paciente');
        
        
        $entity->setfechaDevolucion(new \DateTime('now'));
        $form = $this->createCreateForm($entity,$paciente,$idpaquetes,$idtratamientos);
        $form->handleRequest($request);

        if($entity->getFlagDevolucion()==0){
            $entity->setPersonaTratamiento(null);
        }
        else{
            $entity->setVentaPaquete(null);
        }
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_devolucion', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Devolucion entity.
     *
     * @param Devolucion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Devolucion $entity,$paciente,$paquete,$tratamientos)
    {
        $form = $this->createForm(new DevolucionType(), $entity, array(
            'action' => $this->generateUrl('admin_devolucion_create', array('paciente' => $paciente, 'paquete' => $paquete, 'tratamiento' => $tratamientos)),
            'method' => 'POST',
        ));
        
             $form
                ->add('ventapaquete', 'entity', 
                  array( 'label'         => 'Paquetes','required'=>false,
                         'empty_value'   => 'Seleccione un paquete...',
                         'class'         => 'DGPlusbelleBundle:VentaPaquete',
                         'query_builder' => function(EntityRepository $r) use ( $paciente, $paquete ){
                                                return $r->createQueryBuilder('s')
                                                         ->innerJoin('s.paquete', 'p')
                                                         
                                                        
                                                        ->andWhere('s.id IN (:paquetes)')
                                                        //->setParameter(':idpac', $paciente)
                                                        ->setParameter('paquetes', $paquete)
                                                    ;   
                                            } ,
                         'attr'=>array(
                         'class'=>'form-control input-sm paqueteDevolucion'
                         )
                       ))
                ->add('personatratamiento', 'entity', 
                  array( 'label'         => 'Tratamientos','required'=>false,
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:PersonaTratamiento',
                         'query_builder' => function(EntityRepository $r) use ( $paciente, $tratamientos ){
                                                return $r->createQueryBuilder('s')
                                                         ->innerJoin('s.tratamiento', 't')
                                                         
                                                        
                                                        ->andWhere('t.id IN (:tratamientos)')
                                                        //->setParameter(':idpac', $paciente)
                                                        ->setParameter('tratamientos', $tratamientos)
                                                    ;   
                                            } ,
                         'attr'=>array(
                         'class'=>'form-control input-sm tratamientoDevolucion'
                         )
                       ));
   

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Devolucion entity.
     *
     * @Route("/new", name="admin_devolucion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Devolucion();
        $em = $this->getDoctrine()->getManager();
        
        //Recuperación del paciente
        $request = $this->getRequest();
        $id= $request->get('id');
        $id = substr($id, 1);
        //Busqueda del paciente
        $paciente = $em->getRepository('DGPlusbelleBundle:Paciente')->find($id);
        
        $paquetes = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->findBy(array('paciente'=>$paciente->getPersona()->getId()));
        $tratamientos = $em->getRepository('DGPlusbelleBundle:PersonaTratamiento')->findBy(array('paciente'=>$paciente->getPersona()->getId()));
        $entity->setPaciente($paciente);
        //var_dump($paciente->getId());
        $idpaquetes=array();
        $idtratamientos=array();
        foreach ($paquetes as $paq){
            $idpaq=$paq->getId();
            //var_dump($idpaq);
            //$cuota=$paq->getCuotas();
            $dql = "SELECT d FROM DGPlusbelleBundle:Devolucion d "
                
                . "WHERE d.ventapaquete=:id AND d.paciente=:idpaciente";
                   
            $abonos=array();
            
            $abonos = $em->createQuery($dql)
                       ->setParameters(array('id'=>$idpaq,'idpaciente'=>$paciente->getId()))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            //var_dump($abonos);
            if(count($abonos)==0){
                array_push($idpaquetes, $idpaq); 
            }
            //var_dump($idpaq);
        }
        //var_dump($abonos);
        //var_dump($idpaquetes);
        //var_dump($abonos);
        //var_dump($paciente->getId());
        //var_dump($paciente->getPersona()->getId());
        foreach ($tratamientos as $tra){
            $idtra=$tra->getId();
            //var_dump($idpaq);
            //$cuota=$paq->getCuotas();
            $dql = "SELECT d FROM DGPlusbelleBundle:Devolucion d "
                
                . "WHERE d.personatratamiento=:id AND d.paciente=:idpaciente";
                   
            $id=array();
            
            $id= $em->createQuery($dql)
                       ->setParameters(array('id'=>$idtra,'idpaciente'=>$paciente->getId()))
                       //->setParameter('mes','_____0'.'1'.'___')
                       ->getResult();
            //var_dump($id);
            if(count($id==0)){
                array_push($idtratamientos, $idtra); 
            }
        }
        
        //var_dump($id);
        //$persona=$paciente->getPersona();
        ///var_dump($persona);
        $entity->setPaciente($paciente);
        
        //var_dump($paciente);
        $form   = $this->createCreateForm($entity,$paciente,$idpaquetes,$idtratamientos);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Devolucion entity.
     *
     * @Route("/{id}", name="admin_devolucion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Devolucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Devolucion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Devolucion entity.
     *
     * @Route("/{id}/edit", name="admin_devolucion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Devolucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Devolucion entity.');
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
    * Creates a form to edit a Devolucion entity.
    *
    * @param Devolucion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Devolucion $entity)
    {
        $form = $this->createForm(new DevolucionType(), $entity, array(
            'action' => $this->generateUrl('admin_devolucion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit','submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }
    /**
     * Edits an existing Devolucion entity.
     *
     * @Route("/{id}", name="admin_devolucion_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Devolucion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Devolucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Devolucion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_devolucion'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Devolucion entity.
     *
     * @Route("/{id}", name="admin_devolucion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Devolucion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Devolucion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_devolucion'));
    }

    /**
     * Creates a form to delete a Devolucion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_devolucion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
