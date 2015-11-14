<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\Producto;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CatalogoProductoRepository extends EntityRepository
{
    public function obtenerProdActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('tprod')
                        ->from('DGPlusbelleBundle:Producto', 'tprod')
                        ->where('tprod.estado = true')
                        ;
    }

}