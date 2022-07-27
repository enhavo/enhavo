<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Enhavo\Bundle\ShopBundle\Document\PDF;

class FurtherPageHelper implements PDFHelperInterface
{
    use PDFHelperTrait;

    public function __construct(
        private $label
    )
    {
    }

    public function render(PDF $pdf, $options = [])
    {
        $this->reset($pdf, $options);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

        $this->setStyle($pdf, $options, Style::STYLE_BOLD);

        $pdf->MultiCell($options['further_page_width'],0,$this->label,0,"L",false,1, $options['further_page_margin_left']);
    }
}
