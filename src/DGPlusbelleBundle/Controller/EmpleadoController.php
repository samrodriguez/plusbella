<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\Empleado;
use DGPlusbelleBundle\Entity\Persona;
use DGPlusbelleBundle\Form\EmpleadoType;
use DGPlusbelleBundle\Form\PersonaType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
/**
 * Empleado controller.
 *
 * @Route("/admin/empleado")
 */
class EmpleadoController extends Controller
{

    /**
     * Lists all Empleado entities.
     *
     * @Route("/", name="admin_empleado")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entity = new Empleado();
        $form = $this->createCreateForm($entity);
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getManager();
        
        $sql = "select per.nombres as pnombre, per.apellidos as papellido,  "
                . "per.direccion as direccion, per.telefono as tel, per.email as email, emp.id as idemp, emp.cargo as cargo, emp.foto as foto, suc.nombre as sucursal , emp.estado as estado "
                . "from empleado emp inner join persona per on emp.persona = per.id inner join sucursal suc on suc.id=emp.sucursal order by per.apellidos";
        
        $rsm->addScalarResult('idemp','idemp');
        $rsm->addScalarResult('pnombre','pnombre');
        //$rsm->addScalarResult('snombre','snombre');
        $rsm->addScalarResult('papellido','papellido');
        //$rsm->addScalarResult('sapellido','sapellido');
        //$rsm->addScalarResult('casada','casada');
        $rsm->addScalarResult('direccion','direccion');
        $rsm->addScalarResult('tel','tel');
        $rsm->addScalarResult('email','email');
        $rsm->addScalarResult('cargo','cargo');
        $rsm->addScalarResult('foto','foto');
        $rsm->addScalarResult('sucursal','sucursal');
        $rsm->addScalarResult('estado','estado');

       
        $empleados = $em->createNativeQuery($sql, $rsm)
                    ->getResult();
        $usuario= $this->get('security.token_storage')->getToken()->getUser();
        $foto = $usuario->getPersona()->getEmpleado()[0]->getFoto();

        return array(
            //'entities' => $entities,
            'empleados' => $empleados,
            'entity' => $entity,
            'form'   => $form->createView(),
            'foto' => $foto,
        );
    }
    /**
     * Creates a new Empleado entity.
     *
     * @Route("/", name="admin_empleado_create")
     * @Method("POST")
     * @Template("DGPlusbelleBundle:Empleado:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Empleado();
        $entity->setEstado(true);
        $entity->setComisionCompleta(0);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        
        
        
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            //$entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find($entity->getId());
            
            if($entity->getFile()!=null){
                $path = $this->container->getParameter('photo.empleado');

                $fecha = date('Y-m-d His');
                $extension = $entity->getFile()->getClientOriginalExtension();
                $nombreArchivo = $entity->getId()."-".$fecha.".".$extension;
                $em->persist($entity);
                $em->flush();
                //var_dump($path.$nombreArchivo);

                $entity->setFoto($nombreArchivo);
                $entity->getFile()->move($path,$nombreArchivo);
                $em->persist($entity);
                $em->flush();
            }
            
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se registro un nuevo empleado",$usuario->getId());
            
            return $this->redirect($this->generateUrl('admin_empleado', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Empleado entity.
     *
     * @param Empleado $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Empleado $entity)
    {
        $form = $this->createForm(new EmpleadoType(), $entity, array(
            'action' => $this->generateUrl('admin_empleado_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar',
                                               'attr'=>
                                                        array('class'=>'btn btn-primary btn-sm')
             ));

        return $form;
    }

    /**
     * Displays a form to create a new Empleado entity.
     *
     * @Route("/new", name="admin_empleado_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Empleado();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Empleado entity.
     *
     * @Route("/{id}", name="admin_empleado_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Empleado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Empleado entity.
     *
     * @Route("/{id}/edit", name="admin_empleado_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Empleado entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        
        //$editForm = $editForm->add('name')->add('file')->getForm();
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Empleado entity.
    *
    * @param Empleado $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Empleado $entity)
    {
        $form = $this->createForm(new EmpleadoType(), $entity, array(
            'action' => $this->generateUrl('admin_empleado_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modificar',
                                               'attr'=>
                                                        array('class'=>'btn btn-primary btn-sm')
            ));

        return $form;
    }
    /**
     * Edits an existing Empleado entity.
     *
     * @Route("/{id}", name="admin_empleado_update")
     * @Method("PUT")
     * @Template("DGPlusbelleBundle:Empleado:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Empleado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        //var_dump($_POST);
        //$nombre =$_FILES["fileToUpload"]["name"];
        //$nombre2 =$_FILES['foto_perfil2'];
        //var_dump($nombre);
        //var_dump($nombre2);
        
        //Manejo del archivo
        /*var_dump($entity->getFile());
        die();*/
        if($entity->getFile()!=null){
            $path = $this->container->getParameter('photo.empleado');

            $fecha = date('Y-m-d His');
            $extension = $entity->getFile()->getClientOriginalExtension();
            $nombreArchivo = $entity->getId()."-".$fecha.".".$extension;

            //var_dump($path.$nombreArchivo);

            $entity->setFoto($nombreArchivo);


            $entity->getFile()->move($path,$nombreArchivo);
        }
        //var_dump($entity->getFile());

        //die();
        if ($editForm->isValid()) {
            $em->flush();
            
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $this->get('bitacora')->escribirbitacora("Se actualizo informacion de un empleado",$usuario->getId());
            
            return $this->redirect($this->generateUrl('admin_empleado'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Empleado entity.
     *
     * @Route("/{id}", name="admin_empleado_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Empleado entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_empleado'));
    }

    /**
     * Creates a form to delete a Empleado entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_empleado_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Deletes a Empleado entity.
     *
     * @Route("/desactivar_empleado/{id}", name="admin_empleado_desactivar", options={"expose"=true})
     * @Method("GET")
     */
    public function desactivarAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        //$form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find($id);
        
         if($entity->getEstado()==0){
            $entity->setEstado(1);
            $exito['regs']=1;//registro activado
        }
        else{
            $entity->setEstado(0);
            $exito['regs']=0;//registro desactivado
        }
        
        $em->persist($entity);
        $em->flush();
        
        return new Response(json_encode($exito));
        
    }
}
