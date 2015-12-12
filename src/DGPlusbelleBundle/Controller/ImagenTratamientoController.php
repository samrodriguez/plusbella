<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\ImagenTratamiento;
use DGPlusbelleBundle\Entity\SesionTratamiento;
use DGPlusbelleBundle\Entity\VentaPaquete;
use DGPlusbelleBundle\Entity\SesionVentaTratamiento;
use DGPlusbelleBundle\Form\ImagenTratamientoType;

/**
 * ImagenTratamiento controller.
 *
 * @Route("/admin/imagentratamiento")
 */
class ImagenTratamientoController extends Controller
{

    /**
     * Lists all ImagenTratamiento entities.
     *
     * @Route("/", name="admin_imagentratamiento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:ImagenTratamiento')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ImagenTratamiento entity.
     *
     * @Route("/", name="admin_imagentratamiento_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:ImagenTratamiento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ImagenTratamiento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_imagentratamiento_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ImagenTratamiento entity.
     *
     * @param ImagenTratamiento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ImagenTratamiento $entity)
    {
        $form = $this->createForm(new ImagenTratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_imagentratamiento_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ImagenTratamiento entity.
     *
     * @Route("/new", name="admin_imagentratamiento_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ImagenTratamiento();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ImagenTratamiento entity.
     *
     * @Route("/{id}", name="admin_imagentratamiento_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ImagenTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ImagenTratamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ImagenTratamiento entity.
     *
     * @Route("/{id}/edit", name="admin_imagentratamiento_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ImagenTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ImagenTratamiento entity.');
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
    * Creates a form to edit a ImagenTratamiento entity.
    *
    * @param ImagenTratamiento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ImagenTratamiento $entity)
    {
        $form = $this->createForm(new ImagenTratamientoType(), $entity, array(
            'action' => $this->generateUrl('admin_imagentratamiento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ImagenTratamiento entity.
     *
     * @Route("/{id}", name="admin_imagentratamiento_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:ImagenTratamiento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:ImagenTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ImagenTratamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_imagentratamiento_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ImagenTratamiento entity.
     *
     * @Route("/{id}", name="admin_imagentratamiento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:ImagenTratamiento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ImagenTratamiento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_imagentratamiento'));
    }

    /**
     * Creates a form to delete a ImagenTratamiento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_imagentratamiento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    
    
    
    
    /**
     * Finds and displays a ImagenTratamiento entity.
     *
     * @Route("/galeriatratamiento/{id}/fotos", name="admin_imagentratamiento_galeria", options={"expose"=true})
     * @Method("GET")
     * @Template("DGPlusbelleBundle:ImagenTratamiento:galeria.html.twig")
     */
    public function galeriatratamientoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        //$entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);
        //var_dump("sdc");
        $dql = "SELECT i.fotoAntes, i.fotoDespues, st.fechaSesion FROM DGPlusbelleBundle:SesionVentaTratamiento st "
                . "JOIN st.imagenTratamiento i "
                . "WHERE st.personaTratamiento=:id";
                
        $imagenes = $em->createQuery($dql)
                       ->setParameter('id',$id)
                       ->getResult();
                       //var_dump($empleados);
        
        //var_dump($imagenes);
        

        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entities' => $imagenes,
            
        );
    }
    
    
    
    
    /**
     * 
     *
     * @Route("/galeriapaquete/{id}/fotos", name="admin_imagenpaquete_galeria", options={"expose"=true})
     * @Method("GET")
     * @Template("DGPlusbelleBundle:ImagenTratamiento:galeria.html.twig")
     */
    public function galeriapaqueteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        //$entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);
        //var_dump("sdc");
        $dql = "SELECT i.fotoAntes, i.fotoDespues, st.fechaSesion FROM DGPlusbelleBundle:SesionTratamiento st "
                . "JOIN st.imagenTratamiento i "
                . "WHERE st.ventaPaquete=:id";

        $imagenes = $em->createQuery($dql)
                       ->setParameter('id',$id)
                       ->getResult();
                       //var_dump($empleados);
        
        //var_dump($imagenes);
        

        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entities' => $imagenes,
            
        );
    }
    
    
    
    
    
    /**
     * 
     *
     * @Route("/galeriaconsulta/{id}/fotos", name="admin_imagenconsulta_galeria", options={"expose"=true})
     * @Method("GET")
     * @Template("DGPlusbelleBundle:ImagenTratamiento:galeriaconsulta.html.twig")
     */
    public function galeriaconsultaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        //$entity = $em->getRepository('DGPlusbelleBundle:VentaPaquete')->find($id);
        //var_dump("sdc");
        $dql = "SELECT i.foto FROM DGPlusbelleBundle:Consulta c "
                . "JOIN c.placas2 i "
                . "WHERE c.id=:id";

        $imagenes = $em->createQuery($dql)
                       ->setParameter('id',$id)
                       ->getResult();
                       //var_dump($empleados);
        
        //var_dump($imagenes);
        

        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entities' => $imagenes,
            
        );
    }
    
    
    
    
}
