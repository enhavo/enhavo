<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Enhavo\Bundle\ShopBundle\Document\PDF;

class BackgroundImageHelper implements PDFHelperInterface
{
    public function __construct(
        private KernelInterface $kernel
    )
    {
    }

    public function render(PDF $pdf, $options = [])
    {
        if (isset($options['background_image'])) {
            $backgroundImagePath = $this->kernel->locateResource($options['background_image']);
            $pdf->Image($backgroundImagePath, 0, 0, 210, 297, '', '', 'T', true);
        }
    }
}
