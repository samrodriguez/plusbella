<?php

namespace DGPlusbelleBundle\Service;

use DGPlusbelleBundle\Report\FacialEsteticaPDF;

class EsteticaFacialFPDFService {
    
    private $pdf;
 
    public function __construct(FacialEsteticaPDF $pdf)
    {
        $this->pdf = $pdf;
    }
    
    public function generarFacialTempPdf($paciente, $sucursal, $fecha, $path, $valores, $opciones){
        $this->pdf->FPDF('P','mm','Letter');
	$this->pdf->SetTopMargin(20);
	$this->pdf->SetLeftMargin(20);
        
        $this->pdf->AddPage();
        $this->pdf->SetFillColor(255);
        
        $this->pdf->SetFont('Times','B',16);
        $this->pdf->setTituloReporte('Reporte de Consulta Estetica Facial');
        $this->pdf->SetTitle('Reporte de Consulta Estetica Facial');
        
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Ln(6);
        
        $this->pdf->Cell(32,27, utf8_decode('Información general del paciente'));
        $this->pdf->Line(20, 43, 200, 43);
        
        $this->pdf->Ln(15);
        $this->mostrarCelda($this->pdf, 32, 'Fecha: ', $fecha);
        $this->mostrarCelda($this->pdf, 18, 'Sucursal: ', $sucursal->getNombre());
        $this->pdf->Ln(7);
        $this->mostrarCelda($this->pdf, 32, 'Nombre: ', $paciente->getPersona()->getNombres().' '.$paciente->getPersona()->getApellidos());
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
        $this->mostrarCelda($this->pdf, 32, 'Expediente No.: ', $paciente->getExpediente()[0]->getNumero());
        
        $sexoPaciente = $paciente->getSexo();

        $sexo = '';
        if($sexoPaciente == 'm'){
            $sexo = 'Masculino';
        }
        if($sexoPaciente == 'f'){
            $sexo = 'Femenino';
        }
        
        $this->mostrarCelda($this->pdf, 18, 'Sexo: ', $sexo);
        $this->pdf->Ln(10);
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Cell(32,27,'Tipo de piel');
        $this->pdf->Line(20, 81, 200, 81);
        $this->pdf->Ln(12);
        $this->pdf->SetFont('Arial','',10);
        $this->pdf->Cell(10);
        $i = 26;
        $cont = 0;
        $h = 88.5;
        
        foreach ($opciones as $key => $value) {
            if($value['detNom'] == 'Tipo de piel' ){
                if($cont == 5){
                    $this->pdf->Ln(10);
                    $this->pdf->Cell(10);
                    $i = 26;
                    $h+=10;
                } 
                
                if(isset($valores)){
                    if(in_array($value['id'], $valores)){

                        $this->pdf->Image($path . 'check_est.jpg', $i, $h, 4.5, 4);
                    } else {
                        $this->pdf->Image($path . 'cuadro_est.jpg', $i, $h, 3.5, 3.5);
                    }
                } else {
                    $this->pdf->Image($path . 'cuadro_est.jpg', $i, $h, 3.5, 3.5);
                }
                    
                $this->pdf->Cell(37, 27, utf8_decode($value['nombre']));
                $i+=37;
                $cont++;
            }
        }
        
        $this->pdf->Ln(15);
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Cell(32, 27, utf8_decode('Necesidades observadas'));
        $this->pdf->Line(20, 118.5, 200, 118.5);
        $this->pdf->Ln(12);
        $this->pdf->SetFont('Arial','',10);
        
        $this->pdf->Cell(10);
        $i = 26;
        $cont = 0;
        $h = 125.5;
        
        foreach ($opciones as $key => $value) {
            if($value['detNom'] == 'Necesidades observadas' ){
                
                if($cont == 3){
                    $this->pdf->Ln(10);
                    $this->pdf->Cell(10);
                    $i = 26;
                    $h+=10;
                }
                
                if(isset($valores)){
                    if(in_array($value['id'], $valores)){
                        $this->pdf->Image($path . 'check_est.jpg', $i, $h, 4.5, 4);
                    } else {
                        $this->pdf->Image($path . 'cuadro_est.jpg', $i, $h, 3.5, 3.5);
                    }
                } else {
                    $this->pdf->Image($path . 'cuadro_est.jpg', $i, $h, 3.5, 3.5);
                }
                
                $this->pdf->Cell(52, 27, utf8_decode($value['nombre']));
                $i+=52;
                $cont++;
            }
        }
        
        $this->pdf->Ln(15);
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Cell(32, 27, utf8_decode('Recomendaciones'));
        $this->pdf->Line(20, 155.5, 200, 155.5);
        $this->pdf->Ln(12);
        $this->pdf->SetFont('Arial','',10);
        
        $this->pdf->Cell(10);
        $i = 26;
        $cont = 0;
        $h = 163;
        $div = 0;
        foreach ($opciones as $key => $value) {
            if($value['detNom'] == 'Recomendaciones' ){
                $div = $cont % 4;
                if($div == 0 && $cont > 0){
                    $this->pdf->Ln(10);
                    $this->pdf->Cell(10);
                    $i = 26;
                    $h+=10;
                }
                
                if(isset($valores)){
                    if(in_array($value['id'], $valores)){
                        $this->pdf->Image($path . 'check_est.jpg', $i, $h, 4.5, 4);
                    } else {
                        $this->pdf->Image($path . 'cuadro_est.jpg', $i, $h, 3.5, 3.5);
                    }
                } else {
                    $this->pdf->Image($path . 'cuadro_est.jpg', $i, $h, 3.5, 3.5);
                }
                    
                $this->pdf->Cell(45, 27, utf8_decode($value['nombre']));
                $i+=45;
                $cont++;
            }
        }
        
        $this->pdf->Ln(20);
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Cell(32, 27, utf8_decode('Observaciones'));
        $this->pdf->Line(20, 208, 200, 208);
        $this->pdf->Ln(12);
        $this->pdf->SetFont('Arial','',10);
        
        $this->pdf->Line(20, 219, 200, 219);
        $this->pdf->Line(20, 226, 200, 226);
        $this->pdf->Line(20, 233, 200, 233);
        $this->pdf->Line(20, 240, 200, 240);
        
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


