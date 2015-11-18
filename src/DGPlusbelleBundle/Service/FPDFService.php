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
        
        $logo = $urlLogo.'persona1.jpg';
        $this->pdf->AddPage();
        $this->pdf->SetFillColor(255);
        //$this->pdf->RoundedRect(10, 10, 190, 283, 3.5, 'DF');
        $this->pdf->SetFont('Arial','B',14);
        $this->pdf->Cell(120,20,$titulo, 1, 0, 'C');
        $this->pdf->Image($logo, 10,15,50);
        //$this->pdf->Image("logo", 10,15,50);
        $this->pdf->Output();
        var_dump($this);
        return $this->pdf;
    }
}
