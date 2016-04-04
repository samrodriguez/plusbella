<?php

namespace DGPlusbelleBundle\Service;

//include_once ('../Resources/jpgraph/src/jpgraph.php');
//include_once '../Resources/jpgraph/src/jpgraph_bar.php';
//include_once '../Resources/jpgraph/src/jpgraph_bar.php';

use DGPlusbelleBundle\Report\ReporteEsteticaPDF;


class EsteticaFPDFService {
    
    private $pdf;
 
    public function __construct(ReporteEsteticaPDF $pdf)
    {
        $this->pdf = $pdf;
    }
    
    public function generarCorporalTempPdf($titulo, $paciente, $sucursal, $estetica, $consulta, $fecha, $path, $valores, $corporal, $opciones){
        $this->pdf->FPDF('P','mm','Letter');
	$this->pdf->SetTopMargin(20);
	$this->pdf->SetLeftMargin(20);
        
        $this->pdf->AddPage();
        $this->pdf->SetFillColor(255);
        
        $this->pdf->SetFont('Times','B',16);
        $this->pdf->setTituloReporte($titulo);
        $this->pdf->SetTitle($titulo);
        
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Ln(6);
        
        $this->pdf->Cell(32,27, utf8_decode('Información general del paciente'));
        $this->pdf->Line(20, 43, 200, 43);
        
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
        $this->pdf->Ln(10);
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Cell(32,27,'Motivo de consulta');
        $this->pdf->Line(20, 81, 200, 81);
        $this->pdf->Ln(12);
        $this->pdf->SetFont('Arial','',10);
        $this->pdf->Cell(10);
        $i = 26;
        
        foreach ($opciones as $key => $value) {
            if($value['detNom'] == 'Tratamiento' ){
                
                if(isset($valores)){
                    if(in_array($value['id'], $valores)){
                        //var_dump($valor);
                        $this->pdf->Image($path . 'check_est.jpg', $i, 88.5, 4.5, 4);
                    } else {
                        $this->pdf->Image($path . 'cuadro_est.jpg', $i, 88.5, 3.5, 3.5);
                    }
                } else {
                    $this->pdf->Image($path . 'cuadro_est.jpg', $i, 88.5, 3.5, 3.5);
                }
                
                $this->pdf->Cell(34, 27, $value['nombre']);
                $i+=34;
            }
        }
        
        $this->pdf->Ln(15);
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Cell(32, 27, utf8_decode('Lectura de composición corporal'));
        $this->pdf->Line(20, 108, 200, 108);
        $this->pdf->Ln(32);
        $this->pdf->SetFont('Arial','',8);
        
        $this->pdf->Line(20, 118, 200, 118);
        $this->pdf->Line(20, 118, 20, 150);
        $this->pdf->Line(200, 118, 200, 150);
        $this->pdf->Line(20, 133, 200, 133);
        $this->pdf->Line(20, 141, 200, 141);
        $this->pdf->Line(20, 150, 200, 150);
        
        $this->pdf->Line(35, 118, 35, 150);
        $this->pdf->Line(60, 118, 60, 150);
        $this->pdf->Line(83, 118, 83, 150);
        $this->pdf->Line(102.5, 118, 102.5, 150);
        $this->pdf->Line(126, 118, 126, 150);
        $this->pdf->Line(141, 133, 141, 150);
        $this->pdf->Line(164, 118, 164, 150);
        $this->pdf->Line(180, 118, 180, 150);
        
        $this->pdf->Cell(15, 27, 'Peso',0,0,'C');
        $this->pdf->Cell(25, 27, '% Grasa Corporal',0,0,'C');
        $this->pdf->Cell(23, 27, '% Agua Corporal',0,0,'C');
        $this->pdf->Cell(21, 27, utf8_decode('Masa músculo'),0,0,'C');
        $this->pdf->Cell(23, 27, utf8_decode('Valoración Física'),0,0,'C');
        $this->pdf->Cell(14, 27, 'DCI/BMR',0,0,'C');
        $this->pdf->Cell(24, 27, utf8_decode('Edad Metabólica'),0,0,'C');
        $this->pdf->Cell(15, 27, utf8_decode('Masa Ósea'),0,0,'C');
        $this->pdf->Cell(20, 27, 'Grasa Visceral',0,0,'C');
        
        $this->pdf->Image($path . 'peso.jpg', 23, 120, 10, 10);
        $this->pdf->Image($path . 'muscle.jpg', 108, 120, 10, 10);
        $this->pdf->Image($path . 'muscle.jpg', 87, 120, 10, 10);
        $this->pdf->Image($path . 'mass_bone.jpg', 167, 120, 10, 10);
        $this->pdf->Image($path . 'bmr_dci.jpg', 139, 120, 10, 10);
        $this->pdf->Image($path . 'body_fat.jpg', 40, 120, 16, 10);
        $this->pdf->Image($path . 'agua_corp.jpg', 67, 120, 10, 10);
        $this->pdf->Image($path . 'visceral.jpg', 185, 120, 10, 10);
        
        $this->pdf->Ln(8);
        $this->pdf->SetFont('Arial','',8);
        
        $ancho = array(15, 25, 23, 20, 23, 15, 23, 16, 20);
        
        foreach ($corporal as $key => $value) {
            $this->pdf->Cell($ancho[$key], 27, $value,0,0,'C');
        }
        
        $this->pdf->Ln(15);
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Cell(32, 27, utf8_decode('Sugerencia'));
        $this->pdf->Line(20, 164, 200, 164);
        $this->pdf->Ln(12);
        $this->pdf->SetFont('Arial','',10);
        
        $this->pdf->Cell(10);
        $i = 26;
        $cont = 0;
        $h = 170.5;
        
        foreach ($opciones as $key => $value) {
            if($value['detNom'] == 'Sugerencia' ){
                
                if($cont == 4){
                    $this->pdf->Ln(12);
                    $this->pdf->Cell(10);
                    $i = 26;
                    $h+=12;
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
                    
                $this->pdf->Cell(42, 27, $value['nombre']);
                $i+=42;
                $cont++;
            }
        }
        
        $this->pdf->Ln(15);
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Cell(32, 27, utf8_decode('Observaciones'));
        $this->pdf->Line(20, 203, 200, 203);
        $this->pdf->Ln(12);
        $this->pdf->SetFont('Arial','',10);
        
        $this->pdf->Line(20, 214, 200, 214);
        $this->pdf->Line(20, 221, 200, 221);
        $this->pdf->Line(20, 228, 200, 228);
        $this->pdf->Line(20, 235, 200, 235);
        
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


