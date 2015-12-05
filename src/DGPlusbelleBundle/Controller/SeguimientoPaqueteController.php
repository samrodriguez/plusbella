<?php

namespace DGPlusbelleBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\SeguimientoPaquete;

/**
 * SeguimientoPaquete controller.
 *
 * @Route("/admin/seguimientopaquete")
 */
class SeguimientoPaqueteController extends Controller
{

    /**
     * Lists all SeguimientoPaquete entities.
     *
     * @Route("/", name="admin_seguimientopaquete")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a SeguimientoPaquete entity.
     *
     * @Route("/{id}", name="admin_seguimientopaquete_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:SeguimientoPaquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SeguimientoPaquete entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
