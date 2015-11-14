<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\Descuento;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CatalogoDescuentoRepository extends EntityRepository
{
    public function obtenerDescActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('tdesc')
                        ->from('DGPlusbelleBundle:Descuento', 'tdesc')
                        ->where('tdesc.estado = true')
                        ;
    }

}