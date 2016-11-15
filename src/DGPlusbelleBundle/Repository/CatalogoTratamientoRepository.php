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
                        ->orderBy('ttrat.nombre','ASC')
                        ;
    }
    public function obtenerConsultaTratActivo()
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('ttrat')
                        ->from('DGPlusbelleBundle:Tratamiento', 'ttrat')
                        ->where('ttrat.estado = true')
                        ->andWhere("upper(ttrat.nombre) LIKE '%CONSULTA%'")
                        ->orderBy('ttrat.nombre','ASC')
                        ;
    }

}