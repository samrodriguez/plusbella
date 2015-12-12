<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\VentaPaquete;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class VentaPaqueteRepository extends EntityRepository
{
    public function obtenerventpaqActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder('v')
                        ->innerJoin('v.paquete', 'p')
						->innerJoin('v.ventaPaquete', 'vp')
						//->innerJoin('v.persona', 'pers')
                       // ->where('pers.id=1')
						->Where('vp.cuotas >0')
						//->andWhere('vp.cuotas >0')
                        ;
    }

}