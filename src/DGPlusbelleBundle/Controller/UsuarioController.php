<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Usuario;
use DGPlusbelleBundle\Form\UsuarioType;
use DGPlusbelleBundle\Form\EditUsuarioType;

/**
 * Usuario controller.
 *
 * @Route("/admin/usuario")
 */
class UsuarioController extends Controller
{

    /**
     * Lists all Usuario entities.
     *
     * @Route("/", name="admin_usuario")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Usuario();
        $form = $this->createCreateForm($entity);
        $entities = $em->getRepository('DGPlusbelleBundle:Usuario')->findAll();

        return array(
            'entities' => $entities,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Usuario entity.
     *
     * @Route("/", name="admin_usuario_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Usuario:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Usuario();
        $entity->setEstado(true);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $entity->setEstado(1);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->setSecurePassword($entity);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_usuario'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Usuario entity.
     *
     * @param Usuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Usuario $entity)
    {
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('admin_usuario_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar','attr'=>array('class'=>'btn btn-success btn-sm')));

        return $form;
    }

    /**
     * Displays a form to create a new Usuario entity.
     *
     * @Route("/new", name="admin_usuario_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Usuario();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Usuario entity.
     *
     * @Route("/{id}", name="admin_usuario_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/{id}/edit", name="admin_usuario_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
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
    * Creates a form to edit a Usuario entity.
    *
    * @param Usuario $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Usuario $entity)
    {
        $form = $this->createForm(new EditUsuarioType(), $entity, array(
            'action' => $this->generateUrl('admin_usuario_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modificar','attr'=>array('class'=>'btn btn-success btn-sm')));

        return $form;
    }
    /**
     * Edits an existing Usuario entity.
     *
     * @Route("/{id}", name="admin_usuario_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Usuario:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $passOriginal = $entity->getPassword();
        
        
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        //obtiene la contraseña actual 
        $current_pass = $entity->getPassword();
        
        $editForm->handleRequest($request);

        if($entity->getPassword()==""){
            $entity->setPassword($passOriginal);
        }
        
        if ($editForm->isValid()) {
            if ($current_pass != $entity->getPassword()) {
                $this->setSecurePassword($entity);
            }
            $em->flush();

            return $this->redirect($this->generateUrl('admin_usuario_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Usuario entity.
     *
     * @Route("/{id}", name="admin_usuario_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Usuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Usuario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_usuario'));
    }

    /**
     * Creates a form to delete a Usuario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_usuario_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    private function setSecurePassword(&$entity) {
        $entity->setSalt(md5(time()));
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
    }
    
    
    
    /**
     * 
     *
     * @Route("/desactivar_usuario/{id}", name="admin_usuario_desactivar", options={"expose"=true})
     * @Method("GET")
     */
    public function desactivarAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        //$form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Usuario')->find($id);
        if($entity->getId()!=1){
            if($entity->getEstado()==0){
                $entity->setEstado(1);
                $exito['regs']=1;//registro activado
            }
            else{
                $entity->setEstado(0);
                $exito['regs']=0;//registro desactivado
            }
        }
        else{
            $exito['regs']=2;//admin no puede ser desactivado
        }
        $em->persist($entity);
        $em->flush();
        
        return new Response(json_encode($exito));
        
    }
    
    /**
     * 
     *
     * @Route("/autorizarmodventapaquete/", name="admin_autorizar_ventapaquete", options={"expose"=true})
     * @Method("POST")
     * @Template()
     */
    public function autorizarmodventapaqueteAction(){
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){    
            $em = $this->getDoctrine()->getManager();
            $username = $this->get('request')->request->get('username');
            $password = $this->get('request')->request->get('password');
            $entity = $em->getRepository('DGPlusbelleBundle:Usuario')->findBy(array('username'=>$username));
            //var_dump($entity);
            if(count($entity)!=0){
                $entity = $entity[0];


                $pass = $password;
                $salt = $entity->getSalt();

                $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
                $password = $encoder->encodePassword($pass, $salt);
                //$entity->setPassword($password);
                //var_dump($pass);
                //var_dump($salt);
                $entity = $em->getRepository('DGPlusbelleBundle:Usuario')->findBy(array('password'=>$password));

                //var_dump($entity);
                //$this->setSecurePassword($entity);
                //var_dump(count($entity));

                if(count($entity)==1){
                    $exito['regs']=0;   //Acceso permitido
                }
                else{
                    $exito['regs']=1;   //Acceso no permitido
                }
            }
            else{
                $exito['regs']=2;   //No existe el usuario
            }
        } else {    
            return new Response('0');              
        }  
        
        return new Response(json_encode($exito));

    }
    
}

