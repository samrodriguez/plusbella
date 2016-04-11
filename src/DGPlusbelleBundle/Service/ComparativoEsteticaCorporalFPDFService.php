<?php

namespace DGPlusbelleBundle\Service;

//include_once ('../Resources/jpgraph/src/jpgraph.php');
//include_once '../Resources/jpgraph/src/jpgraph_bar.php';
//include_once '../Resources/jpgraph/src/jpgraph_bar.php';

use DGPlusbelleBundle\Report\ComparativoEsteticaCorporalPDF;


class ComparativoEsteticaCorporalFPDFService {
    
    private $pdf;
 
    public function __construct(ComparativoEsteticaCorporalPDF $pdf)
    {
        $this->pdf = $pdf;
    }
    
    public function generarCorporalTempPdf($titulo, $paciente, $sucursal, $estetica, $consulta, $fecha, $path, $corporal, $comparativo){
        $this->pdf->FPDF('P','mm','Letter');
	$this->pdf->SetTopMargin(20);
	$this->pdf->SetLeftMargin(15);
        
        $this->pdf->AddPage();
        $this->pdf->SetFillColor(255);
        
        $this->pdf->SetFont('Times','B',16);
        $this->pdf->setTituloReporte($titulo);
        $this->pdf->SetTitle($titulo);
        
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Ln(6);
        
        $this->pdf->Cell(32,27, utf8_decode('Información general del paciente'));
        $this->pdf->Line(15, 43, 205, 43);
        
        $this->pdf->Ln(15);
        $this->mostrarCelda($this->pdf, 32, 'Fecha: ', $fecha);
        $this->mostrarCelda($this->pdf, 18, 'Sucursal: ', $sucursal->getNombre());
        $this->pdf->Ln(7);
        $this->mostrarCelda($this->pdf, 32, 'Nombre: ', utf8_decode($paciente->getPersona()->getNombres()).' '.utf8_decode($paciente->getPersona()->getApellidos()));
        if($paciente->getFechaNacimiento()!=null){
           $fecha = $paciente->getFechaNacimiento()->format("Y-m-d");    
           list($Y,$m,$d) = explode("-",$fecha);
        
        $edad = date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y;    
        }
        else{
        $edad="N/A";
        }
        
        $this->mostrarCelda($this->pdf, 18, 'Edad: ', $edad.' '.utf8_decode('años'));
        
        $this->pdf->Ln(7);
        
        if($paciente->getExpediente()[0]){
            $this->mostrarCelda($this->pdf, 32, 'Expediente No.: ', utf8_decode($paciente->getExpediente()[0]->getNumero()));
        } else {
            $this->mostrarCelda($this->pdf, 32, 'Expediente No.: ', 'No definido');
        }
        
        $sexoPaciente = $paciente->getSexo();
        
        $sexo = '';
        if($sexoPaciente == 'm' || $sexoPaciente == 'M'){
            $sexo = 'Masculino';
        } elseif($sexoPaciente == 'f' || $sexoPaciente == 'F'){
            $sexo = 'Femenino';
        } else {
            $sexo = $sexoPaciente;
        }
        
        $this->mostrarCelda($this->pdf, 18, 'Sexo: ', $sexo);
        $this->pdf->Ln(31);

        $this->pdf->SetFont('Arial','',8);
        
        $this->pdf->Line(15, 80, 205, 80);
        
        $this->pdf->Line(15, 95, 205, 95);
        $this->pdf->Line(15, 103, 205, 103);
        $h = 103;
        
        for ($i = 1; $i <= 14; $i++) {
            $h+=9;
            $this->pdf->Line(15, $h, 205, $h);
            
        }
        
        $this->pdf->Line(15, 80, 15, $h);
        $this->pdf->Line(205, 80, 205, $h);
        
        $this->pdf->Line(28, 80, 28, $h);
        $this->pdf->Line(40, 80, 40, $h);
        $this->pdf->Line(65, 80, 65, $h);
        $this->pdf->Line(88, 80, 88, $h);
        $this->pdf->Line(107.5, 80, 107.5, $h);
        $this->pdf->Line(131, 80, 131, $h);
        $this->pdf->Line(146, 95, 146, $h);
        $this->pdf->Line(169, 80, 169, $h);
        $this->pdf->Line(185, 80, 185, $h);
        
        $this->pdf->Cell(13, 27, 'Fecha',0,0,'C');
        $this->pdf->Cell(12, 27, 'Peso',0,0,'C');
        $this->pdf->Cell(25, 27, '% Grasa Corporal',0,0,'C');
        $this->pdf->Cell(23, 27, '% Agua Corporal',0,0,'C');
        $this->pdf->Cell(21, 27, utf8_decode('Masa músculo'),0,0,'C');
        $this->pdf->Cell(23, 27, utf8_decode('Valoración Física'),0,0,'C');
        $this->pdf->Cell(14, 27, 'DCI/BMR',0,0,'C');
        $this->pdf->Cell(24, 27, utf8_decode('Edad Metabólica'),0,0,'C');
        $this->pdf->Cell(15, 27, utf8_decode('Masa Ósea'),0,0,'C');
        $this->pdf->Cell(20, 27, 'Grasa Visceral',0,0,'C');
        
        $this->pdf->Image($path . 'peso.jpg', 31, 85, 6, 6);
        $this->pdf->Image($path . 'muscle.jpg', 113, 82, 10, 10);
        $this->pdf->Image($path . 'muscle.jpg', 92, 82, 10, 10);
        $this->pdf->Image($path . 'mass_bone.jpg', 172, 82, 10, 10);
        $this->pdf->Image($path . 'bmr_dci.jpg', 144, 82, 10, 10);
        $this->pdf->Image($path . 'body_fat.jpg', 45, 82, 16, 10);
        $this->pdf->Image($path . 'agua_corp.jpg', 72, 82, 10, 10);
        $this->pdf->Image($path . 'visceral.jpg', 190, 82, 10, 10);
        
        $this->pdf->Ln(8);
        $this->pdf->SetFont('Arial','',8);
        
        $ancho = array(15, 25, 23, 20, 23, 15, 23, 16, 20);
        
        
        //var_dump($comparativo);
        $cont = 0;
        $j = 0;
        foreach ($comparativo as $key => $value) {
            $this->pdf->Cell(0.25);
            $this->pdf->Cell(13, 27, $value->getFecha()->format("d/m/y"),0,0,'C');
            $this->pdf->Cell(12, 27, $value->getPeso(),0,0,'C');
            $this->pdf->Cell(25, 27, $value->getGrasaCorporal(),0,0,'C');
            $this->pdf->Cell(23, 27, $value->getAguaCorporal(),0,0,'C');
            $this->pdf->Cell(21, 27, $value->getMasaMusculo(),0,0,'C');
            $this->pdf->Cell(23, 27, $value->getValoracionFisica(),0,0,'C');
            $this->pdf->Cell(14, 27, $value->getdciBmr(),0,0,'C');
            $this->pdf->Cell(24, 27, $value->getEdadMetabolica(),0,0,'C');
            $this->pdf->Cell(15, 27, $value->getMasaOsea(),0,0,'C');
            $this->pdf->Cell(20, 27, $value->getGrasaVisceral(),0,0,'C');
            $cont++;
            
            if($cont == 14){
            $this->pdf->Ln(40);
            
            $this->pdf->Cell(13, 27, 'Fecha',0,0,'C');
            $this->pdf->Cell(12, 27, 'Peso',0,0,'C');
            $this->pdf->Cell(25, 27, '% Grasa Corporal',0,0,'C');
            $this->pdf->Cell(23, 27, '% Agua Corporal',0,0,'C');
            $this->pdf->Cell(21, 27, utf8_decode('Masa músculo'),0,0,'C');
            $this->pdf->Cell(23, 27, utf8_decode('Valoración Física'),0,0,'C');
            $this->pdf->Cell(14, 27, 'DCI/BMR',0,0,'C');
            $this->pdf->Cell(24, 27, utf8_decode('Edad Metabólica'),0,0,'C');
            $this->pdf->Cell(15, 27, utf8_decode('Masa Ósea'),0,0,'C');
            $this->pdf->Cell(20, 27, 'Grasa Visceral',0,0,'C');
            $this->pdf->Ln(10);

            $this->pdf->Line(15, 30, 205, 30);
            $h = 30;

            for ($i = 1; $i <= 22; $i++) {
                $h+=9;
                $this->pdf->Line(15, $h, 205, $h);
            }

            $this->pdf->Line(15, 30, 15, $h);
            $this->pdf->Line(205, 30, 205, $h);

            $this->pdf->Line(28, 30, 28, $h);
            $this->pdf->Line(40, 30, 40, $h);
            $this->pdf->Line(65, 30, 65, $h);
            $this->pdf->Line(88, 30, 88, $h);
            $this->pdf->Line(107.5, 30, 107.5, $h);
            $this->pdf->Line(131, 30, 131, $h);
            $this->pdf->Line(146, 30, 146, $h);
            $this->pdf->Line(169, 30, 169, $h);
            $this->pdf->Line(185, 30, 185, $h);
            
            } else {
                $this->pdf->Ln(9);
            }
            
            if($cont > 14){
                $j++;
            }
            
            if($j == 21){
            $this->pdf->Ln(40);
            $this->pdf->Cell(13, 27, 'Fecha',0,0,'C');
            $this->pdf->Cell(12, 27, 'Peso',0,0,'C');
            $this->pdf->Cell(25, 27, '% Grasa Corporal',0,0,'C');
            $this->pdf->Cell(23, 27, '% Agua Corporal',0,0,'C');
            $this->pdf->Cell(21, 27, utf8_decode('Masa músculo'),0,0,'C');
            $this->pdf->Cell(23, 27, utf8_decode('Valoración Física'),0,0,'C');
            $this->pdf->Cell(14, 27, 'DCI/BMR',0,0,'C');
            $this->pdf->Cell(24, 27, utf8_decode('Edad Metabólica'),0,0,'C');
            $this->pdf->Cell(15, 27, utf8_decode('Masa Ósea'),0,0,'C');
            $this->pdf->Cell(20, 27, 'Grasa Visceral',0,0,'C');
            $this->pdf->Ln(10);

            $this->pdf->Line(15, 30, 205, 30);
            $h = 30;

            for ($i = 1; $i <= 22; $i++) {
                $h+=9;
                $this->pdf->Line(15, $h, 205, $h);
            }

            $this->pdf->Line(15, 30, 15, $h);
            $this->pdf->Line(205, 30, 205, $h);

            $this->pdf->Line(28, 30, 28, $h);
            $this->pdf->Line(40, 30, 40, $h);
            $this->pdf->Line(65, 30, 65, $h);
            $this->pdf->Line(88, 30, 88, $h);
            $this->pdf->Line(107.5, 30, 107.5, $h);
            $this->pdf->Line(131, 30, 131, $h);
            $this->pdf->Line(146, 30, 146, $h);
            $this->pdf->Line(169, 30, 169, $h);
            $this->pdf->Line(185, 30, 185, $h);
            $j = 0;
            
            }
        }
        
        $this->pdf->Output();
        // return $pdf;
    }
    
    public function mostrarCelda($pdf, $ancho, $encabezado, $data){
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($ancho, 27, $encabezado);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(85, 27, $data);
    }
}


