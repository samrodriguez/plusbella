<?php

namespace DGPlusbelleBundle\Service;

use DGPlusbelleBundle\Report\ReportePDF;

class FPDFService {
    
    private $pdf;
 
    public function __construct(ReportePDF $pdf)
    {
        $this->pdf = $pdf;
    }
    
    public function toPdf($titulo, $urlLogo)
    {
        //$logo = $urlLogo.'logo.png';
         $logo = $urlLogo.'sonodigest.jpg';
        
        $this->pdf->FPDF('P','mm','Letter');
	$this->pdf->SetTopMargin(20);
	$this->pdf->SetLeftMargin(20);
       
        $this->pdf->AddPage();
        $this->pdf->SetFillColor(255);
        //$this->pdf->RoundedRect(10, 10, 190, 283, 3.5, 'DF');
        $this->pdf->SetFont('Arial','B',14);
        $this->pdf->Cell(120,20,$titulo, 0, 0, 'C');
        
        $this->pdf->Image($logo, 140,20, 50, 20);
        $this->pdf->Output();
        
        return $this->pdf;
    }
}
