<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Plantilla;
use DGPlusbelleBundle\Entity\DetalleOpcionesPlantilla;
use DGPlusbelleBundle\Form\PlantillaType;

/**
 * Plantilla controller.
 *
 * @Route("/admin/plantilla")
 */
class PlantillaController extends Controller
{

    /**
     * Lists all Plantilla entities.
     *
     * @Route("/", name="admin_plantilla")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = new Plantilla();
        $form = $this->createCreateForm($entity);        
        $entities = $em->getRepository('DGPlusbelleBundle:Plantilla')->findAll();
        $parametros = $em->getRepository('DGPlusbelleBundle:DetallePlantilla')->findAll();
       // var_dump($parametros);
        return array(
            'entities' => $entities,
            'parametros' => $parametros,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Plantilla entity.
     *
     * @Route("/", name="admin_plantilla_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Plantilla:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Plantilla();
        $entity->setEstado(true);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $parameters = $request->request->all();
        //var_dump($parameters);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setClinica(0);
            $em->persist($entity);
            $em->flush();
            $coleccion = $entity->getPlacas();
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            //var_dump($entity);
            //var_dump($coleccion);
            foreach ( $coleccion as $key => $row) {//itera la coleccion inicial de todos los parametros
                //var_dump($key);
            
                foreach ( $parameters as $clave => $row2) {//itera la coleccion de los atributos de cada parametro
                    $nombreOpcion = explode('-',$clave);
                    //echo "Clave de parameters ".$clave;
                    
                    
                    if (strpos($clave, 'opcionTag') !== false) {//verifica que cada clave tenga el prefijo opcionTag
                        //echo 'true';
                        
                        if ( $key==$nombreOpcion[1] ) {//Obtiene el valor de cada iteración para almacenarse en la tabla
                            $detalleOpcionesPlantilla = new DetalleOpcionesPlantilla();
                            
                            $detalleOpcionesPlantilla->setNombre($row2);
                            $detalleOpcionesPlantilla->setDetallePlantilla($entity->getPlacas()[$key]);
                            $detalleOpcionesPlantilla->setTipoParametro('Textarea');
                            $em->persist($detalleOpcionesPlantilla);
                            $em->flush($detalleOpcionesPlantilla);
                            //var_dump($entity->getPlacas());
                            //var_dump($clave);
                            //echo $nombreOpcion[1];
                            
                            //var_dump($row2);//valor del atributo a guardar en campo nombre de detalle_opciones_plantilla
                            //var_dump($detalleOpcionesPlantilla);
                        }
                    }
                }
                
//                if($key=='opcionTag'){
//                    
//                }
//                var_dump($key);
            }
            
            $detallePlantilla = $em->getRepository('DGPlusbelleBundle:detallePlantilla')->findBy(array('plantilla'=>$entity->getId()));

            foreach ($detallePlantilla as $value) {
                $value->setTipoParametro('Textarea');
                $em->merge($value);
                $em->flush();
            }
            
            $this->get('bitacora')->escribirbitacora("Se creo una nueva plantilla de reporte",$usuario->getId());
            
            return $this->redirect($this->generateUrl('admin_plantilla', array('id' => $entity->getId())));
        }
        
        

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Plantilla entity.
     *
     * @param Plantilla $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Plantilla $entity)
    {
        $form = $this->createForm(new PlantillaType(), $entity, array(
            'action' => $this->generateUrl('admin_plantilla_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')
            
            ));

        return $form;
    }

    /**
     * Displays a form to create a new Plantilla entity.
     *
     * @Route("/new", name="admin_plantilla_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Plantilla();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Plantilla entity.
     *
     * @Route("/{id}", name="admin_plantilla_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Plantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Plantilla entity.
     *
     * @Route("/{id}/edit", name="admin_plantilla_edit" , options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Plantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
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
    * Creates a form to edit a Plantilla entity.
    *
    * @param Plantilla $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Plantilla $entity)
    {
        $form = $this->createForm(new PlantillaType(), $entity, array(
            'action' => $this->generateUrl('admin_plantilla_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit',array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-success btn-sm')));

        return $form;
    }
    /**
     * Edits an existing Plantilla entity.
     *
     * @Route("/{id}", name="admin_plantilla_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Plantilla:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Plantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
        }
        $originalCollection = $entity->getPlacas();
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            
            
            foreach ($originalCollection as $row) {
                
                
                if (false === $entity->getPlacas()->contains($row)) {
                    
                    // if you wanted to delete the Tag entirely, you can also do that
                    $em->remove($row);
                    $entity->removePlacas($row);
                    $em->flush();
                }
            }
            
            
            
            $em->flush();

            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se actualizo informacion de un reporte de plantilla",$usuario->getId());
            
             return $this->redirect($this->generateUrl('admin_plantilla'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Plantilla entity.
     *
     * @Route("/{id}", name="admin_plantilla_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Plantilla')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Plantilla entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_plantilla'));
    }

    /**
     * Creates a form to delete a Plantilla entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_plantilla_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
