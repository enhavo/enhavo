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
    }
}
