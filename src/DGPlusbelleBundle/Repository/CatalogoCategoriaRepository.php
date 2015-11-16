<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\Categoria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CatalogoCategoriaRepository extends EntityRepository
{
    public function obtenerCatActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('tcat')
                        ->from('DGPlusbelleBundle:Categoria', 'tcat')
                        ->where('tcat.estado = true')
                        ;
    }

}