<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Enhavo\Bundle\ShopBundle\Document\PDF;

trait PDFHelperTrait
{
    protected function reset(PDF $pdf, $options)
    {
        $pdf->setCellMargins("", "", "", "");
        $pdf->SetCellPaddings(0,0,0,0);
        $this->setStyle($pdf, $options);
    }

    protected function setStyle(PDF $pdf, $options, $style = Style::STYLE_NORMAL)
    {
        $pdf->SetFontSize($options['font_size']);
        $pdf->SetFont($options['font']);
        $pdf->SetTextColor($options['font_color']);

        if ($style === Style::STYLE_BOLD) {
            $pdf->SetFontSize($options['font_size']);
            $pdf->SetFont($options['font_bold']);
            $pdf->SetTextColor($options['font_color']);
        } else if ($style === Style::STYLE_HIGHLIGHT) {
            $pdf->SetFontSize($options['font_highlight_size']);
            $pdf->SetFont($options['font_bold']);
            $pdf->SetTextColor($options['font_color']);
        } else if ($style === Style::STYLE_HEADER) {
            $pdf->SetFontSize($options['font_header_size']);
            $pdf->SetFont($options['font_bold']);
            $pdf->SetTextColor($options['font_color']);
        } else if ($style === Style::STYLE_SMALL) {
            $pdf->SetFontSize($options['font_small_size']);
            $pdf->SetFont($options['font']);
            $pdf->SetTextColor($options['font_color']);
        }
    }
}
