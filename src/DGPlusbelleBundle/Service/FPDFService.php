<?php

namespace DGPlusbelleBundle\Service;

use DGPlusbelleBundle\Report\ReportePDF;

class FPDFService {
    
    private $pdf;
 
    public function __construct(ReportePDF $pdf)
    {
        $this->pdf = $pdf;
    }
    
    public function toPdf($titulo, $consulta, $encabezado)
    {
        
        $this->pdf->FPDF('P','mm','Letter');
	$this->pdf->SetTopMargin(20);
	$this->pdf->SetLeftMargin(20);
        
        $this->pdf->AddPage();
        $this->pdf->SetFillColor(255);
        
        $this->pdf->SetFont('Arial','B',16);
        $this->pdf->Cell(180,32,$titulo, 0, 0, 'C');
        
        
        
        
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Ln(26);
        
        //$this->pdf->Cell(32,27,'Informacion general del paciente');
       // $this->pdf->Line(20, 43, 200, 43);
        
        $this->pdf->Ln(15);
        //$this->mostrarCelda($pdf, 32, 'Fecha: ', $consulta[0]->getConsulta()->getFechaConsulta()->format("d/m/Y"));
        
        
        
        $this->pdf->Output();
        return $this->pdf;
    }
    
    public function generarPlantilla($urlLogo, $consulta, $medico){
        $pdf  = new \FPDF_FPDF();
        $pdi  = new \FPDF_FPDI();
        
        
        $logo = $urlLogo.'sonodigest.jpg';
        
        $pdf->FPDF('P','mm','Letter');
	$pdf->SetTopMargin(0);
	$pdf->SetLeftMargin(20);
        $pdf->SetAutoPageBreak(true, 6);
        $pdf->AddPage();
        $pdf->SetFillColor(255);
        
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(120,32,$consulta[0]->getDetallePlantilla()->getPlantilla()->getNombre());
        
        $pdf->Image($logo, 150, 5, 50, 20);
        $pdf->Line(20, 25.5, 200, 25.5);
        $pdf->Line(20, 26, 200, 26);
        
        $pdf->SetFont('Arial','B',13);
        $pdf->Ln(26);
        
        $pdf->Cell(32,27,'Informacion general del paciente');
        $pdf->Line(20, 43, 200, 43);
        
        $pdf->Ln(15);
        $this->mostrarCelda($pdf, 32, 'Fecha: ', $consulta[0]->getConsulta()->getFechaConsulta()->format("d/m/Y"));
        $pdf->Ln(7);
        $this->mostrarCelda($pdf, 32, 'Nombre: ', $consulta[0]->getConsulta()->getPaciente()->getPersona()->getNombres().' '.$consulta[0]->getConsulta()->getPaciente()->getPersona()->getApellidos());
        
        $fecha = $consulta[0]->getConsulta()->getPaciente()->getFechaNacimiento()->format("Y-m-d");        
        list($Y,$m,$d) = explode("-",$fecha);
        $edad = date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y;    
        
        $this->mostrarCelda($pdf, 13, 'Edad: ', $edad /*.' '. htmlentities('Años', ENT_QUOTES,'UTF-8')*/);
        
        $pdf->Ln(7);
        $this->mostrarCelda($pdf, 32, 'Expediente No.: ', $consulta[0]->getConsulta()->getPaciente()->getExpediente()[0]->getNumero());
        
        $sexoPaciente = $consulta[0]->getConsulta()->getPaciente()->getSexo();
        $sexo = '';
        if($sexoPaciente == 'm'){
            $sexo = 'Masculino';
        } else {
            $sexo = 'Femenino';
        }
        
        $this->mostrarCelda($pdf, 13, 'Sexo: ', $sexo);
        
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(32,27,'Resultado de consulta');
        $pdf->Line(20, 81, 200, 81);
        $pdf->Ln(18);
        foreach ($consulta as $value) {
            $pdf->SetX(25);
            $pdf->SetFont('Arial','B',10);
            $pdf->MultiCell(40, 5, $value->getDetallePlantilla()->getNombre().': ', 0, 'J', false);
            $pdf->SetX(66.6);
            $pdf->SetFont('Arial','',10);
            $pdf->MultiCell(135, 5, $value->getValorDetalle(), 0, 'J', false);
            $this->pdf->Ln(10);
            
        }
        //$tam = count($consulta);
        
        //$espacio = 
        //$pdf->Ln(55);
        $pdf->SetY(241);
        $pdf->SetX(20);
         $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 27, 'Colonia Escalon, Calle Cuscatlan, No. 448, San Salvador.');
        $pdf->Ln(5);
        $pdf->SetX(20);
        $pdf->Cell(85, 27, 'Entre la 83 Av. y 85 Av. Sur. Tel.: 25192857');
        
        $pdf->SetFont('Arial','',11);
        $pdf->SetY(235);
        $pdf->SetX(156);
        $pdf->Cell(88, 27, $medico['nombre']);
        
        $pdf->Ln(5);
        $pdf->SetX(160);
        $pdf->Cell(85, 27, $medico['cargo']);
        
        $pdf->Ln(5);
        $pdf->SetX(178);
        $pdf->Cell(85, 27, $medico['codigo']);
        
        $pdf->Output();
        
       // return $pdf;
    }
    
    public function mostrarCelda($pdf, $ancho, $encabezado, $data){
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($ancho, 27, $encabezado);
            $pdf->SetFont('Arial','',10);
        $pdf->Cell(85, 27, $data);
    }
}