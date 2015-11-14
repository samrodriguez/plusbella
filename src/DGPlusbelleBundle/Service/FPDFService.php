<?php

namespace DGPlusbelleBundle\Service;

use DGPlusbelleBundle\Report\ReportePDF;

class FPDFService {
    
        private $pdf;
 
    public function __construct(ReportePDF $pdf)
    {
        $this->pdf = $pdf;
    }
 
    public function toPdf()
    {
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        $this->pdf->SetFont('Times','',12);
 
        for($i=1;$i<=40;$i++) {
            $this->pdf->Cell(0,10,'Printing line number '.$i,0,1);
        }
 
        return $this->pdf;
    }
}
