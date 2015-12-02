<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
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
        
        $request = $this->getRequest();
        $idpaquetes = $request->get('paquete');
        $idtratamientos = $request->get('tratamiento');
        $paciente = $request->get('paciente');
        $form   = $this->createCreateForm($entity, $paciente, $idpaquetes, $idtratamientos);
       // $form   = $this->createCreateForm($entity, $paciente, $idtratamientos);
        //$form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $entity->setFechaAbono(new \DateTime('now'));
        
        if($entity->getFlagAbono()==0){
            $entity->setPersonaTratamiento(null);
            
        }
        else{
            $entity->setVentaPaquete(null); 
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_abono_show', array('id' => $entity->getId())));
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
                         'class'=>'form-control input-sm '
                         )
                       ));
                                            
         $form->add('personaTratamiento', 'entity', 
                  array( 'label'         => 'Tratamiento','required'=>false,
                         'empty_value'   => 'Seleccione un tratamiento...',
                         'class'         => 'DGPlusbelleBundle:PersonaTratamiento',
                         'query_builder' => function(EntityRepository $r) use ( $paciente, $tratamiento ){
                                                return $r->createQueryBuilder('s')
                                                         ->innerJoin('s.tratamiento', 'p')
                                                        // ->innerJoin('s.paciente', 'pac')
                                                        ->where('s.cuotas > 0')
                                                       // ->andWhere('pac.id = :idpac')
                                                        ->andWhere('s.id IN (:tratamientos)')
                                                        //->setParameter(':idpac', $paciente)
                                                        ->setParameter('tratamientos', $tratamiento)
                                                    ;   
                                            } ,
                         'attr'=>array(
                         'class'=>'form-control input-sm '
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
     * @Route("/new", name="admin_abono_new")
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
        $entity->setPaciente($paciente);
        
        $form   = $this->createCreateForm($entity, $paciente->getPersona()->getId(), $idpaquetes, $idtratamientos);
        //$form   = $this->createCreateForm($entity, $paciente->getPersona()->getId(), $idtratamientos);
        
        return array(
            'entity' => $entity,
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
}
