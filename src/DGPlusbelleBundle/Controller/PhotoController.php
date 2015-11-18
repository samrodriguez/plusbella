<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Categoria controller.
 *
 * @Route("/photo")
 */
class PhotoController extends Controller
{

    /**
     * Lists all Categoria entities.
     *
     * @Route("/test", name="plusbelle_photo_test")
     * @Method("GET")
     * @Template()
     */
    public function testAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DGPlusbelleBundle:Empleado')->find(2);

        return array(
            'entity' => $entity,
        );
        
    }
   
    /**
     * Lists all Categoria entities.
     *
     * @Route("/crop", name="plusbelle_photo")
     * @Method("POST")
     * @Template()
     */
    public function photoAction(Request $request)
    {
        $path = $this->container->getParameter('photo.empleado');
        $empleado = str_replace("/app/../", "/", $path);
        
        $path = $this->container->getParameter('photo.tmp');
        $tmp = str_replace("/app/../", "/", $path);
        
        $photo = $this->get('plussbelle.photo');

        $src  =  $request->request->get('avatar_src');
        $data =  $request->request->get('avatar_data');
        $file =  $request->files->get('avatar_file');
        $id   =  $request->request->get('id');
        
        $random = $this->randomNumberText();
        $photo->cropping($src,$data,$file,$id,$empleado,$tmp,$random);
        

        return new JsonResponse(array(
            'state'  => 200,
            'message' => $photo->getMsg(),
            'result' =>  $photo->getResult()
          ));
    }
    
    
    public function randomNumberText(){
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $aleatorio = "";
        for($i=0;$i<12;$i++) {
            $aleatorio .= substr($str,rand(0,62),1);
        }
        return $aleatorio;
        
    }
}
