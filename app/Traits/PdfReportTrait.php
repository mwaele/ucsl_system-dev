<?php

namespace App\Traits;

use App\Helpers\PdfHelper;
use Barryvdh\DomPDF\Facade\Pdf;

trait PdfReportTrait
{
    /**
     * Render a view as a PDF with page numbers and return as a download response.
     *
     * @param string $view     Blade view path
     * @param array  $data     Data to pass to the view
     * @param string $filename Filename for the downloaded PDF
     * @param string $paper    Paper size (default: a4)
     * @param string $orientation Orientation ('portrait' or 'landscape')
     *
     * @return \Illuminate\Http\Response
     */
    public function renderPdfWithPageNumbers(
        string $view,
        array $data,
        string $filename,
        string $paper = 'a4',
        string $orientation = 'portrait'
    ) {
        $pdf = Pdf::loadView($view, $data)
            ->setPaper($paper, $orientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->render();

        PdfHelper::addPageNumbers($dompdf);

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
