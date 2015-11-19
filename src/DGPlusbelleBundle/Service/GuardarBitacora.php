<?php

namespace DGPlusbelleBundle\Service;

use DGPlusbelleBundle\Entity\Bitacora;
use Doctrine\ORM\EntityManager;
/**
 * Bitacora
 *
 * 
 */
class GuardarBitacora
// class Bitacora 
{
    protected $em;
    public function __construct(EntityManager $em){
        $this->em=$em;
    }
    public function escribirbitacora($mensaje,$id){
        
        // $em Doctrine\ORM\EntityManager;
        // $em = EntityManager;
        $entity  = new Bitacora();
        //var_dump($id);
        $usuario = $this->em->getRepository('DGPlusbelleBundle:Usuario')->find($id);
        $entity->setAccion($mensaje);
        $entity->setFechaAccion(new \DateTime ('now'));
        $entity->setUsuario($usuario);
        // var_dump($entity);
        $this->em->persist($entity);
        $this->em->flush();
    }       
}