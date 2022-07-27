<?php

namespace Enhavo\Bundle\ShopBundle\Document;

class PDF extends \TCPDF
{


    public function __construct(
        $orientation = 'P',
        $unit = 'mm',
        $format = 'A4',
        $unicode = true,
        $encoding = 'UTF-8',
        $diskCache = false,
        $pdfa = false
    ) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskCache, $pdfa);

        $this->SetMargins(0, 0, 0);
        $this->SetPrintHeader(false);
        $this->SetPrintFooter(false);
        $this->SetHeaderMargin(0);
        $this->SetFooterMargin(0);
        $this->SetAutoPageBreak(true, 00);
        $this->SetImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->SetFontSubsetting(true);
    }
}
