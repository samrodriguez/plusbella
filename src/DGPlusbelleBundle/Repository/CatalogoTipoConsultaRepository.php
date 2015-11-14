<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\TipoConsulta;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CatalogoTipoConsultaRepository extends EntityRepository
{
    public function obtenerTipoConsActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('ttcons')
                        ->from('DGPlusbelleBundle:TipoConsulta', 'ttcons')
                        ->where('ttcons.estado = true')
                        ;
    }

}