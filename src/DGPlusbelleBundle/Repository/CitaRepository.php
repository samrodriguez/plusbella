<?php
namespace DGPlusbelleBundle\Repository;

use Doctrine\ORM\EntityRepository;


class CitaRepository extends EntityRepository {
    
    public function buscarCitas()
    {
       $dql = "SELECT ci, pac, emp, hor, tra FROM DGPlusbelleBundle:Cita ci "
               . "INNER JOIN ci.paciente pac "
               . "INNER JOIN ci.empleado emp "
               . "INNER JOIN ci.horario hor "
               . "INNER JOIN ci.tratamiento tra ";
       $repositorio = $this->getEntityManager()->createQuery($dql);       
       return $repositorio->getResult();	
    }
}
