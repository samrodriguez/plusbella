<?php

namespace DGPlusbelleBundle\Service;

include_once ('../web/Resources/jpgraph/src/jpgraph.php');
include_once '../web/Resources/jpgraph/src/jpgraph_bar.php';
//include_once '../web/Resources/jpgraph/src/jpgraph_bar.php';

use DGPlusbelleBundle\Report\ReportePDF;


class FPDFService {
    
    private $pdf;
 
    public function __construct(ReportePDF $pdf)
    {
        $this->pdf = $pdf;
    }
    
    public function generarPdfPorRango($titulo, $encabezado, $anchoCol, $consulta, $sangria, $fechaInicio, $fechaFin)
    {
        
        $this->pdf->FPDF('P','mm','Letter');
	$this->pdf->SetTopMargin(20);
	$this->pdf->SetLeftMargin(20);
        
        $this->pdf->AddPage();
        $this->pdf->SetFillColor(255);
        
        $this->pdf->SetFont('Times','B',16);
        $this->pdf->Cell(180,32,$titulo, 0, 0, 'C');
        
        $this->pdf->SetFont('Times','B',13);
        $this->pdf->Ln(8);
        
        if ( $fechaInicio != null && $fechaFin != null){
            $fechaini = explode('-', $fechaInicio);
            $fechf = explode('-', $fechaFin);

            $this->pdf->Cell(180,32, 'Del periodo de '.$fechaini[2].'/'.$fechaini[1].'/'.$fechaini[0].' al '.$fechf[2].'/'.$fechf[1].'/'.$fechf[0], 0, 0, 'C');
        } else {
            $this->pdf->Cell(180,32, 'Del periodo de 01/01/2015 al 31/12/2015', 0, 0, 'C');
        }
        $this->pdf->Ln(30);
        $this->pdf->SetFont('Times','B',11);
        
        $this->pdf->Cell($sangria);
        $this->pdf->SetWidths($anchoCol);
        
	$this->pdf->Row($encabezado);
        $this->pdf->SetFont('Times','',11);
        
        foreach ($consulta as $fila) {
            $data = array();
            $cont = 0;
            $this->pdf->Cell($sangria);
            
           foreach ($fila as $valor) {
               $data[$cont] = $valor;
               $cont++;
            }
            
            $this->pdf->Row($data);
        }
        
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
            $pdf->MultiCell(40, 5, $value->getDetallePlantilla()->getNombre().': ', 0, 'L', false);
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
    
    
    
    public function generarTotalesPdfPorRango($titulo, $encabezado, $anchoCol, $consulta, $sangria, $fechaInicio, $fechaFin)
    {
        
        $this->pdf->FPDF('P','mm','Letter');
        $this->pdf->SetTopMargin(20);
        $this->pdf->SetLeftMargin(20);
        
        $this->pdf->AddPage();
        $this->pdf->SetFillColor(255);
        
        $this->pdf->SetFont('Times','B',16);
        $this->pdf->Cell(180,32,$titulo, 0, 0, 'C');
        
        $this->pdf->SetFont('Times','B',13);
        $this->pdf->Ln(8);
        
        if ( $fechaInicio != null && $fechaFin != null){
            $fechaini = explode('-', $fechaInicio);
            $fechf = explode('-', $fechaFin);

            $this->pdf->Cell(180,32, 'Del periodo de '.$fechaini[2].'/'.$fechaini[1].'/'.$fechaini[0].' al '.$fechf[2].'/'.$fechf[1].'/'.$fechf[0], 0, 0, 'C');
        } else {
            $this->pdf->Cell(180,32, 'Del periodo de 01/01/2015 al 31/12/2015', 0, 0, 'C');
        }
        $this->pdf->Ln(30);
        $this->pdf->SetFont('Times','B',11);
        
        $this->pdf->Cell($sangria);
        $this->pdf->SetWidths($anchoCol);
        
        //$this->pdf->Row($encabezado);
        $this->pdf->Cell(47,5,$encabezado['0'], 'LTRB', 0, 'L');
            
        $this->pdf->Cell(30,5,$encabezado['1'], 'LTRB', 0, 'R');
        $this->pdf->Ln(5);
        $this->pdf->SetFont('Times','',11);
        $total=0;
        foreach ($consulta as $fila) {
            $data = array();
            $cont = 0;
            $this->pdf->Cell($sangria);
            
           foreach ($fila as $valor) {
               $data[$cont] = $valor;
               $cont++;
            }
            //var_dump($data['0']);
            //var_dump($this->pdf->Row($data));
            //$this->pdf->Row($data);
            $this->pdf->Cell(47,5,$data['0'], 'LTRB', 0, 'L');
            
            $this->pdf->Cell(30,5,$data['1'], 'LTRB', 0, 'R');
            $total = $total + $data['1'];
            $this->pdf->Ln(5);
        }
        $this->pdf->SetFont('Times','B',11);
        $this->pdf->Cell($sangria);
        $this->pdf->Cell(47,5,"Total", 'LTRB', 0, 'L');
            
        $this->pdf->Cell(30,5,  number_format($total,2,'.',''), 'LTRB', 0, 'R');
        
        $this->pdf->Output();
        return $this->pdf;
    }
    
    
    
    
    
    
    
    
    
    
    public function generarPlantilla2($urlLogo, $consulta, $medico){
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
        //var_dump($consulta[0]->getSesionVentaTratamientoReceta()->getFechaSesion());
        $pdf->Ln(15);
        //$this->mostrarCelda($pdf, 32, 'Fecha: ', $consulta[0]->getConsulta()->getFechaConsulta()->format("d/m/Y"));
        $this->mostrarCelda($pdf, 32, 'Fecha: ', $consulta[0]->getSesionVentaTratamientoReceta()->getFechaSesion()->format("d/m/Y"));
        
        $pdf->Ln(7);
        $this->mostrarCelda($pdf, 32, 'Nombre: ', $consulta[0]->getSesionVentaTratamientoReceta()->getPersonaTratamiento()->getPaciente()->getNombres().' '.$consulta[0]->getSesionVentaTratamientoReceta()->getPersonaTratamiento()->getPaciente()->getApellidos());
        
        $fecha = $consulta[0]->getSesionVentaTratamientoReceta()->getFechaSesion()->format("Y-m-d");        
        list($Y,$m,$d) = explode("-",$fecha);
        $edad = date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y;    
        
        $this->mostrarCelda($pdf, 13, 'Edad: ', $edad /*.' '. htmlentities('Años', ENT_QUOTES,'UTF-8')*/);
        
        $pdf->Ln(7);
        //$this->mostrarCelda($pdf, 32, 'Expediente No.: ', $consulta[0]->getConsulta()->getPaciente()->getExpediente()[0]->getNumero());
        $this->mostrarCelda($pdf, 32, 'Expediente No.: ', $consulta[0]->getSesionVentaTratamientoReceta()->getPersonaTratamiento()->getPaciente()->getPaciente()[0]->getExpediente()[0]->getNumero());
        
        $sexoPaciente = $consulta[0]->getSesionVentaTratamientoReceta()->getPersonaTratamiento()->getPaciente()->getPaciente()[0]->getSexo();
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
            $pdf->MultiCell(40, 5, $value->getDetallePlantilla()->getNombre().': ', 0, 'L', false);
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
    
    
    
    
    
    
    
    
    
    
}


