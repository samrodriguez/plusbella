<?php

namespace DGPlusbelleBundle\Service;

use DGPlusbelleBundle\Report\ComparativoEsteticaBotoxPDF;

class ComparativoEsteticaBotoxFPDFService {
    
    private $pdf;
 
    public function __construct(ComparativoEsteticaBotoxPDF $pdf)
    {
        $this->pdf = $pdf;
    }
    
    public function generarCorporalTempPdf($titulo, $paciente, $sucursal, $estetica, $consulta, $fecha, $path, $corporal, $botox){
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
        $this->pdf->Ln(18);

        $this->pdf->SetFont('Arial','',8);
        
        $this->pdf->Image($path . 'rpt_botox.jpg', 15, 80, 40, 50);
        $this->pdf->Image($path . 'rpt_botox.jpg', 15, 130, 40, 50);
        $this->pdf->Image($path . 'rpt_botox.jpg', 15, 180, 40, 50);
        
        $this->pdf->Line(58, 80, 205, 80);
        $this->pdf->Line(58, 90, 205, 90);
        $h = 90;
        
        for ($i = 1; $i <= 15; $i++) {
            $h+=9;
            $this->pdf->Line(58, $h, 205, $h);            
        }
        
        $this->pdf->Line(205, 80, 205, $h+9);
        $this->pdf->Line(191, $h+9, 205, $h+9);
        $this->pdf->Line(58, 80, 58, $h);
        $this->pdf->Line(81, 80, 81, $h);
        $this->pdf->Line(94, 80, 94, $h);
        $this->pdf->Line(122, 80, 122, $h);
        $this->pdf->Line(138, 80, 138, $h);
        $this->pdf->Line(168, 80, 168, $h);
        $this->pdf->Line(191, 80, 191, $h+9);
        
        $this->pdf->Cell(45);
        $this->pdf->Cell(20, 27, 'Area a inyectar',0,0,'C');
        $this->pdf->Cell(15, 27, 'Unidades',0,0,'C');
        $this->pdf->Cell(25, 27, 'Fecha de caducidad',0,0,'C');
        $this->pdf->Cell(18, 27, 'Lote No.',0,0,'C');
        $this->pdf->Cell(28, 27, utf8_decode('Marca del producto'),0,0,'C');
        $this->pdf->Cell(25, 27, utf8_decode('No. Aplicación'),0,0,'C');
        $this->pdf->Cell(12, 27, 'Valor',0,0,'C');
        
        $this->pdf->Ln(8);
        $this->pdf->SetFont('Arial','',8);
        
        $cont = 0;
        $j = 0;
        
        //var_dump($botox);
        
        foreach ($botox as $key => $value) {
            $this->pdf->Cell(45);
            $this->pdf->Cell(20, 27, $value[0],0,0,'C');
            $this->pdf->Cell(15, 27, $value[1],0,0,'C');
            
            $fechaCad = explode('-', $value[2]);
//            var_dump($fechaCad);
            $this->pdf->Cell(25, 27, $fechaCad[2].'-'.$fechaCad[1].'-'.$fechaCad[0],0,0,'C');
            //$this->pdf->Cell(25, 27, $value[2],0,0,'C');
            $this->pdf->Cell(19, 27, $value[3],0,0,'C');
            $this->pdf->Cell(28, 27, $value[4],0,0,'C');
            $this->pdf->Cell(25, 27, $value[5],0,0,'C');
            $this->pdf->Cell(12, 27, $value[6],0,0,'C');
            $cont++;
            
            if($cont == 10){
            $this->pdf->Ln(40);
            
//            $this->pdf->Cell(13, 27, 'Fecha',0,0,'C');
//            $this->pdf->Cell(12, 27, 'Peso',0,0,'C');
//            $this->pdf->Cell(25, 27, '% Grasa Corporal',0,0,'C');
//            $this->pdf->Cell(23, 27, '% Agua Corporal',0,0,'C');
//            $this->pdf->Cell(21, 27, utf8_decode('Masa músculo'),0,0,'C');
//            $this->pdf->Cell(23, 27, utf8_decode('Valoración Física'),0,0,'C');
//            $this->pdf->Cell(14, 27, 'DCI/BMR',0,0,'C');
//            $this->pdf->Cell(24, 27, utf8_decode('Edad Metabólica'),0,0,'C');
//            $this->pdf->Cell(15, 27, utf8_decode('Masa Ósea'),0,0,'C');
//            $this->pdf->Cell(20, 27, 'Grasa Visceral',0,0,'C');
//            $this->pdf->Ln(10);

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
        
        //$this->pdf->SetY(210);
        $this->pdf->SetXY(20, 220); 
        $this->pdf->SetFont('Arial','B',10);
        $this->pdf->Cell(0, 27, 'Recomendaciones:',0,0);
        //$this->pdf->Ln(10);
        $this->pdf->SetDrawColor(255,255,255);
        $this->pdf->SetFont('Arial','',10);
        $this->pdf->SetXY(20, 236); 
        $this->pdf->SetWidths(array(185));
        $this->pdf->Row(array($botox[0][7]));
        $this->pdf->SetDrawColor(0,0,0);
        
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


