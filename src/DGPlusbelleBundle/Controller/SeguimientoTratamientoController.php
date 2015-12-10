<?php

namespace DGPlusbelleBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGPlusbelleBundle\Entity\SeguimientoTratamiento;

/**
 * SeguimientoTratamiento controller.
 *
 * @Route("/admin/seguimientotratamiento")
 */
class SeguimientoTratamientoController extends Controller
{

    /**
     * Lists all SeguimientoTratamiento entities.
     *
     * @Route("/", name="admin_seguimientotratamiento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a SeguimientoTratamiento entity.
     *
     * @Route("/{id}", name="admin_seguimientotratamiento_show", options ={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DGPlusbelleBundle:SeguimientoTratamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SeguimientoTratamiento entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
