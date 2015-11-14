<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\Paquete;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CatalogoPaqueteRepository extends EntityRepository
{
    public function obtenerpaqActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('tpaq')
                        ->from('DGPlusbelleBundle:Paquete', 'tpaq')
                        ->where('tpaq.estado = true')
                        ;
    }

}