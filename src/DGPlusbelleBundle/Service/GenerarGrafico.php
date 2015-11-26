<?php

namespace DGPlusbelleBundle\Service;

include_once ('../web/Resources/jpgraph/src/jpgraph.php');
include_once ('../web/Resources/jpgraph/src/jpgraph_bar.php');

class GenerarGrafico {
     public function mostrarGrafico()
    {
         // Se define el array de datos
        $datosy=array(25,16,24,5,8,31);

        // Creamos el grafico
        $grafico = new \Graph(500,250);
        $grafico->SetScale('textlin');

        // Ajustamos los margenes del grafico-----    (left,right,top,bottom)
        $grafico->SetMargin(40,30,30,40);

        // Creamos barras de datos a partir del array de datos
        $bplot = new \BarPlot($datosy);

        // Configuramos color de las barras
        $bplot->SetFillColor('#479CC9');

        //Añadimos barra de datos al grafico
        $grafico->Add($bplot);

        // Queremos mostrar el valor numerico de la barra
        $bplot->value->Show();

        // Configuracion de los titulos
        $grafico->title->Set('Mi primer grafico de barras');
        $grafico->xaxis->title->Set('Titulo eje X');
        $grafico->yaxis->title->Set('Titulo eje Y');

        $grafico->title->SetFont(FF_FONT1,FS_BOLD);
        $grafico->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
        $grafico->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

        // Se muestra el grafico
        $grafico->Stroke();
     }
     
     public function toPdf($titulo, $consulta, $encabezado)
    {
        //$data1y = array(4,8,6);
        
        $this->pdf->FPDF('P','mm','Letter');
	$this->pdf->SetTopMargin(20);
	$this->pdf->SetLeftMargin(20);
        
        $this->pdf->AddPage();
        $this->pdf->SetFillColor(255);
        
        $this->pdf->SetFont('Arial','B',16);
        $this->pdf->Cell(180,32,$titulo, 0, 0, 'C');
        
        $this->pdf->SetFont('Arial','B',13);
        $this->pdf->Ln(26);
        
        
        $this->pdf->Ln(15);
       
        //$graph = new \Graph(270, 200, 'auto');
        // Se define el array de datos
        $datosy=array(25,16,24,5,8,31);

        // Creamos el grafico
        $grafico = new \Graph(500,250);
        $grafico->SetScale('textlin');

        // Ajustamos los margenes del grafico-----    (left,right,top,bottom)
        $grafico->SetMargin(40,30,30,40);

        // Creamos barras de datos a partir del array de datos
        $bplot = new \BarPlot($datosy);

        // Configuramos color de las barras
        $bplot->SetFillColor('#479CC9');

        //Añadimos barra de datos al grafico
        $grafico->Add($bplot);

        // Queremos mostrar el valor numerico de la barra
        $bplot->value->Show();

        // Configuracion de los titulos
        $grafico->title->Set('Ingreso de paquetes');
        $grafico->xaxis->title->Set('Meses');
        $grafico->yaxis->title->Set('Ingresos ($)');

        $grafico->title->SetFont(FF_FONT1,FS_BOLD);
        $grafico->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
        $grafico->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
        
        $nombreGrafico = "Barras";
        @unlink("$nombreGrafico.png");
        // Se muestra el grafico
        $grafico->Stroke("$nombreGrafico.png");
        //img = $grafico->Stroke(_IMG_HANDLER); 
        //Aqui agrego la imagen que acabo de crear con jpgraph
        $this->pdf->Image("$nombreGrafico.png", $this->pdf->GetX()+20, $this->pdf->GetY(), 120, 90);
        //$this->pdf->GDImage($img,50,50,110,70);
        
        $this->pdf->Output();
        return $this->pdf;
    }
}
