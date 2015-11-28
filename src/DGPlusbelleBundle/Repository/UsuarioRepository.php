<?php

namespace DGPlusbelleBundle\Repository;

use DGPlusbelleBundle\Entity\Usuario;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class UsuarioRepository extends EntityRepository
{
    public function obtenerUsuarioActivo() 
    {
		return $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('us')
					->from('DGPlusbelleBundle:Usuario', 'us')
					->innerJoin('us.persona','per')
					->innerjoin('per.empleado','emp')
					->where('us.username = 2')
					
					;
    }

}