<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\Sucursal;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CatalogoSucursalRepository extends EntityRepository
{
    public function obtenerSucActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('tsuc')
                        ->from('DGPlusbelleBundle:Sucursal', 'tsuc')
                        ->where('tsuc.estado = true')
                        ;
    }

}