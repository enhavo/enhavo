<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Enhavo\Bundle\ShopBundle\Document\PDF;

class SubjectHelper implements PDFHelperInterface
{
    use PDFHelperTrait;

    public function __construct(
        private string $subject,
    )
    {
    }

    public function render(PDF $pdf, $options = [])
    {
        $this->reset($pdf, $options);

        $this->setStyle($pdf, $options, Style::STYLE_HEADER);

        $pdf->MultiCell($options['subject_width'],0, $this->subject,0,"L",false,1, $options['margin_left'], $options['subject_margin_top']);
    }
}
