<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\Estetica;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class EsteticaRepository extends EntityRepository
{
//    public function obtenerRecetasActivo() 
//    {
//        return $this->getEntityManager()
//                        ->createQueryBuilder()
//                        ->select('p')
//                        ->from('DGPlusbelleBundle:Estetica', 'p')
//                        ->where('p.nombre LIKE :word ')
//                        ->andWhere('p.estado = 1')
//                        ->setParameter('word', '%Receta%')
//                        ;
//    }
    
    
    public function otrosDocActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('p')
                        ->from('DGPlusbelleBundle:Estetica', 'p')
                        ->where('p.nombre NOT LIKE :word ')
                        ->andWhere('p.estado = 1')
                        ->setParameter('word', '%Receta%')
                        ;
    }

}