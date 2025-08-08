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
        $pageCount = $canvas->get_page_count();
        
        // Replace with widest possible text for measurement
        $widestText = str_replace(
            ['{PAGE_NUM}', '{PAGE_COUNT}'],
            [$pageCount, $pageCount],
            $format
        );

        $textWidth = $canvas->get_text_width($widestText, $font, $fontSize);
        $x = ($width - $textWidth) / 2;
        $y = $height - $offsetY;

        // Call only once, Dompdf will replace placeholders per page
        $canvas->page_text($x, $y, $format, $font, $fontSize, $color);
    }
}
