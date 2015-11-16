<?php

namespace DGPlusbelleBundle\Service;

use DGPlusbelleBundle\Report\ReportePDF;

class FPDFService {
    
    /*    private $pdf;
 
    public function __construct(ReportePDF $pdf)
    {
        $this->pdf = $pdf;
    }*/
 
    public function toPdf()
    {
        $pdf  = new \FPDF_FPDF();
        $pdi  = new \FPDF_FPDI();

        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,'Hello World!');
        $pdf->Output();
        
        return $pdf;
    }
}
