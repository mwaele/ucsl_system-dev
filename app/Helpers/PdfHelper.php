<?php

namespace App\Helpers;

use Dompdf\Dompdf;

class PdfHelper
{
    /**
     * Add page numbers to a Dompdf instance after rendering.
     *
     * Automatically centers horizontally and places near the bottom
     * regardless of page size or orientation.
     *
     * @param Dompdf $dompdf
     * @param string $format Page number format (e.g., "Page {PAGE_NUM} of {PAGE_COUNT}")
     * @param float $offsetY Distance from bottom in points
     * @param string $fontName Font name (default: Helvetica)
     * @param int $fontSize Font size in points
     * @param array $color RGB array (default: black)
     */
    public static function addPageNumbers(
        Dompdf $dompdf,
        string $format = "Page {PAGE_NUM} of {PAGE_COUNT}",
        float $offsetY = 28,
        string $fontName = "Helvetica",
        int $fontSize = 10,
        array $color = [0, 0, 0]
    ): void {
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->get_font($fontName, "normal");

        $width = $canvas->get_width();
        $height = $canvas->get_height();
        
        // Center the placeholder string (Dompdf replaces it per page automatically)
        $textWidth = $canvas->get_text_width($format, $font, $fontSize);
        $x = ($width - $textWidth) / 2;
        $y = $height - $offsetY;

        // Only call once – Dompdf will render correct numbers on each page
        $canvas->page_text($x, $y, $format, $font, $fontSize, $color);
    }
}
