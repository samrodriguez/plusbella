<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * SesionTratamiento
 *
 * @ORM\Table(name="sesion_tratamiento", indexes={@ORM\Index(name="fk_consulta_cita1_idx", columns={"cita"}), @ORM\Index(name="fk_consulta_tipo_consulta1_idx", columns={"tipo_consulta"}), @ORM\Index(name="fk_consulta_paciente1_idx", columns={"paciente"}), @ORM\Index(name="fk_consulta_tratamiento1_idx", columns={"tratamiento_id"})})
 * @ORM\Entity
 */
class SesionTratamiento {
    
}
