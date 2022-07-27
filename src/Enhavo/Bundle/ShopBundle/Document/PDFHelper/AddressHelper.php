<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Sylius\Component\Addressing\Model\AddressInterface;
use Enhavo\Bundle\ShopBundle\Document\PDF;

class AddressHelper implements PDFHelperInterface
{
    use PDFHelperTrait;

    public function __construct(
        private AddressInterface $address,
    )
    {
    }

    public function render(PDF $pdf, $options = [])
    {
        $this->reset($pdf, $options);

        if ($this->address->getCompany()) {
            $pdf->MultiCell($options['address_width'], 0, $this->address->getCompany(), 0, "L", false, 1, $options['margin_left'], $options['address_margin_top']);
            $pdf->MultiCell($options['address_width'], 0, $this->address->getFirstName(), 0, "L", false, 1, $options['margin_left']);
        } else {
            $pdf->MultiCell($options['address_width'], 0, $this->address->getFirstName(),0,"L",false,1,$options['margin_left'], $options['address_margin_top']);
        }

        $pdf->MultiCell($options['address_width'], 0, $this->address->getStreet(), 0, "L", false, 1 ,$options['margin_left']);
        $pdf->MultiCell($options['address_width'], 0, $this->address->getPostcode(), 0, "L", false, 1, $options['margin_left']);

        $country = $this->address->getCountryCode();
        $pdf->MultiCell($options['address_width'], 0, $country, 0, "L", false, 1, $options['margin_left']);

        if (!$this->address->getCompany()) {
            $pdf->MultiCell($options['address_width'], 0, "", 0, "L", false, 1, $options['margin_left']);
        }
    }
}
