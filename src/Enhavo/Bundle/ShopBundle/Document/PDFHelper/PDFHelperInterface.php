<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Enhavo\Bundle\ShopBundle\Document\PDF;

interface PDFHelperInterface
{
    public function render(PDF $pdf, $options = []);
}
