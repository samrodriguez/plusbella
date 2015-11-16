<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\Empleado;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CatalogoEmpleadoRepository extends EntityRepository
{
    public function obtenerEmpActivo() 
    {
        return $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('temp')
                        ->from('DGPlusbelleBundle:Empleado', 'temp')
                        ->where('temp.estado = true')
                        ;
    }

}