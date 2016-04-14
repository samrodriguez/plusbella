<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\Plantilla;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class PlantillaRepository extends EntityRepository
{
    public function obtenerRecetasActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('p')
                        ->from('DGPlusbelleBundle:Plantilla', 'p')
                        ->where('p.nombre LIKE :word ')
                        ->andWhere('p.estado = 1')
                        ->setParameter('word', '%Receta%')
                        ;
    }
    
    
    public function otrosDocActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('p')
                        ->from('DGPlusbelleBundle:Plantilla', 'p')
                        ->where('p.nombre NOT LIKE :word ')
                        ->andWhere('p.estado = 1')
                        ->setParameter('word', '%Receta%')
                        ;
    }

}