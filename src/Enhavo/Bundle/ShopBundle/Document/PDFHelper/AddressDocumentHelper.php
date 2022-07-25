<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Addressing\Model\AddressInterface;

class AddressDocumentHelper implements PDFHelperInterface
{
    public function __construct(
        private AddressInterface $address,
    )
    {
    }

    public function handle(PDF $pdf, OrderInterface $order, $options = [])
    {
        $this->setCellMargins("", "", "", "");
        $this->SetFont($options['font']);
        $this->SetFont($options['font_bold']);

        if ($this->address->getCompany()) {
            $this->MultiCell($options['address_width'], 0, $this->address->getCompany(), 0, "L", false, 1, $options['margin_left'], $options['margin_top']);
            $this->MultiCell($options['address_width'], 0, $this->address->getFirstName(), 0, "L", false, 1, $options['margin_left']);
        } else {
            $this->MultiCell($options['address_width'], 0, $this->address->getFirstName(),0,"L",false,1,$options['margin_left'], $options['margin_top']);
        }

        $this->MultiCell($options['address_width'], 0, $this->address->getStreet(), 0, "L", false, 1 ,$options['margin_left']);
        $this->MultiCell($options['address_width'], 0, $this->address->getPostcode(), 0, "L", false, 1, $options['margin_left']);

        $country = $this->address->getCountryCode();
        $this->MultiCell($options['address_width'], 0, $country, 0, "L", false, 1, $options['margin_left']);

        if (!$this->address->getCompany()) {
            $this->MultiCell($options['address_width'], 0, "", 0, "L", false, 1, $options['margin_left']);
        }
    }
}
