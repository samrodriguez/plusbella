<?php

namespace DGPlusbelleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/default")
 */
class DefaultController extends Controller
{

/**
     * @Route("/email", name="default_email")
     * @Method("GET")
     * @Template("DGPlusbelleBundle:Default:email.html.twig")
     */
    public function emailAction()
    {
        $emails = array('melitor@gmail.com','elman@digitalitygarage.com','anthony@digitalitygarage.com','nestor@digitalitygarage.com','marvinvigilm@gmail.com');
        $this->get('plussbelle.mailer')->setEmail($emails);
        return array(
            'mensaje' => 'correo envidado',
        );
    }
 

    /**
     * @Route("/grafico", name="default_grafico")
     * @Method("GET")
     */
    public function graficoAction()
    {
        $name = 'Grafico';
        // Chart
        $series = array(array("name" => "Data Serie Name",    "data" => array(1,2,4,5,6,3,8)));

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Chart Title');
        $ob->xAxis->title(array('text'  => "Horizontal axis title"));
        $ob->yAxis->title(array('text'  => "Vertical axis title"));
        $ob->series($series);

            
        return $this->render('DGPlusbelleBundle:Default:grafico.html.twig', array(
        'chart' => $ob
        ));
    }
   

     /**
     * @Route("/pagination/{page}", requirements={"page" = "\d+"}, defaults={"page"=1},name="default_pagination")
     * @Method("GET")
     * @Template("DGPlusbelleBundle:Default:pagination.html.twig")
     */
    public function paginationAction($page,Request $request)
    {
       $articulos = array(
        array('id' => 1, 'title' => 'Articulo numero 1', 'created' => '2011-01-01'),
        array('id' => 2, 'title' => 'Articulo numero 2', 'created' => '2011-01-01'),
        array('id' => 3, 'title' => 'Articulo numero 3', 'created' => '2011-01-01'),
        array('id' => 1, 'title' => 'Articulo numero 1', 'created' => '2011-01-01'),
        array('id' => 2, 'title' => 'Articulo numero 2', 'created' => '2011-01-01'),
        array('id' => 3, 'title' => 'Articulo numero 3', 'created' => '2011-01-01'),
        array('id' => 1, 'title' => 'Articulo numero 1', 'created' => '2011-01-01'),
        array('id' => 2, 'title' => 'Articulo numero 2', 'created' => '2011-01-01'),
        array('id' => 3, 'title' => 'Articulo numero 3', 'created' => '2011-01-01'),
           
    );
        $em = $this->getDoctrine()->getManager();
        $articulos = $em->getRepository('DGPlusbelleBundle:Test')->findAll();
        $entities  = $this->paginacion($articulos,'parametro','default_pagination',$page);  
        return  array(
        
            'entities' => $entities,
        );
    }
     private function paginacion($entities,$search,$route,$page){
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($entities,$page,5);
        $pagination->setUsedRoute($route);
        $pagination->setParam('search', $search);
        return $pagination;
    }
    /**
     * @Route("/calendar", name="default_calendar")
     * @Method("GET")
     * @Template("DGPlusbelleBundle:Default:calendar.html.twig")
     */
    public function calendarAction()
    {
       $name = 'Celendario';

        return array(
            'title' => $name,
        );
    }
    /**
     * @Route("/", name="default_")
     * @Method("GET")
     */
     public function indexAction()
    {
        return $this->render('DGPlusbelleBundle:Default:index.html.twig', array('name' => 'samuel'));
    }

    /**
     * Lists all Cita entities.
     *
     * @Route("/pdf", name="admin_pdf")
     * 
     */
    public function pdfAction()
    {
        $titulo = 'Reporte de Videoendoscopia';
        $pdf = $this->get('fpdf_printer')->toPdf($titulo);
        
        var_dump($pdf);
     
     
    }

}
