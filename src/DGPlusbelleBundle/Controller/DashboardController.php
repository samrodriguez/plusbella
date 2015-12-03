<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Dashboard controller.
 *
 * @Route("/admin")
 */
class DashboardController extends Controller 
{
   /**
     *
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction()
    {
        $usuario= $this->get('security.token_storage')->getToken()->getUser();
        $empleado = $usuario->getPersona()->getEmpleado()[0];
        //var_dump($usuario->getPersona());
        $foto= $this->getParameter('photo.empleado').$empleado->getFoto();
        //var_dump($foto);
        
        //var_dump($usuario->getPersona()->getEmpleado()[0]->getFoto());
        
        
        return $this->render('DGPlusbelleBundle:Dashboard:dashboard.html.twig'/*,array('foto'=>$foto,'empleado'=>$empleado)*/);     
        
    }
    
    /**
     *
     * @Route("/menu", name="menu")
     */
    public function MenuAction()
    {
        return $this->render('DGPlusbelleBundle:Dashboard:Menu.html.twig');     
        
    }
}
