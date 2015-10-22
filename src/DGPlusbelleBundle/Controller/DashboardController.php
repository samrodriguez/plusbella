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
        return $this->render('DGPlusbelleBundle:Dashboard:dashboard.html.twig');     
        
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
