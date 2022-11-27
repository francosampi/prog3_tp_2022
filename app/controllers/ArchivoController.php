<?php

/*
TODO: PDF VENTA DE PEDIDOS

use Fpdf\Fpdf;
require_once('./models/Arma.php');

class ArchivoController
{
    public function ImprimirArmasPDF($request, $response, $args)
    {
        $armas = Arma::obtenerTodos();

        if (!empty($armas))
        {
            $pdf = new FPDF();
            $pdfCarpeta = './media/PDF';

            $pdf -> AddPage('L', 'A4');
            $pdf -> SetFont('Arial', 'B', 16);
            $pdf -> Cell(120, 12, 'Programacion 3 - Segundo Parcial - Listado de armas');
            $pdf -> Ln(3);

            $pdf -> SetFont('Arial', '', 16);
            $pdf -> Cell(60, 32, 'Sampietro Franco - 3ero Div. C');
            $pdf -> Ln(32);

            $pdf -> SetFont('Arial', '', 12);
            $pdf->SetFillColor(175, 122, 197);
            
            $pdf->Cell(10, 10,'id',1,0,'C', true);
            $pdf->Cell(70, 10,'nombre',1,0,'C', true);
            $pdf->Cell(50, 10,'precio',1,0,'C', true);
            $pdf->Cell(75, 10,'nacionalidad',1,0,'C', true);
            $pdf->Ln();

            $pdf->SetFillColor(218, 247, 166);
            $color=false;
            
            //TABLA
            foreach ($armas as $arma) {
                $pdf -> Cell(10, 10, $arma->id,1,0,'C',$color);
                $pdf -> Cell(70, 10, $arma->nombre,1,0,'C',$color);
                $pdf -> Cell(50, 10, $arma->precio.'$',1,0,'C',$color);
                $pdf -> Cell(75, 10, $arma->nacionalidad,1,0,'C',$color);
                $pdf -> Ln();
                $color=!$color;
            }

            $pdf -> Output('I', 'Armas.pdf');
            
            $payload = json_encode(array("mensaje" => "El PDF ha sido generado con exito"));
        }
        else
            $payload = json_encode(array("mensaje" => "No se han encontrado armas..."));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/pdf');
    }
}

?>
*/