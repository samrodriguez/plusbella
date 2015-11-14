<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\Tratamiento;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CatalogoTratamientoRepository extends EntityRepository
{
    public function obtenerTratActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('ttrat')
                        ->from('DGPlusbelleBundle:Tratamiento', 'ttrat')
                        ->where('ttrat.estado = true')
                        ;
    }

}