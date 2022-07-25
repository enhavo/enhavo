<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

interface PDFHelperInterface
{
    public function handle(PDF $pdf, OrderInterface $order, $options = []);
}
