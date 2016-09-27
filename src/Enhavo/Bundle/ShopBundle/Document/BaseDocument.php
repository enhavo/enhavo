<?php
/**
 * Document.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Document;

use Sylius\Component\Addressing\Model\AddressInterface;
use TCPDF;

class BaseDocument extends TCPDF
{
    const MARGIN_LEFT = 22;
    const MARGIN_TOP = 54;
    const ADDRESS_WIDTH = 60;
    const META_DATA_WIDTH = 40;
    const META_DATA_VALUE_WIDTH = 25;
    const SUBJECT_WIDTH = 80;
    const SUM_WIDTH = 60;
    const SUM_VALUE_WIDTH = 40;
    const META_DATA_MARGIN_TOP = 79;
    const SUBJECT_MARGIN_TOP = 108;
    const ITEM_TABLE_MARGIN_TOP = 118;
    const SUM_MARGIN_LEFT = 100;
    const SUM_VALUE_MARGIN_LEFT = 167;
    const STD_COLOR = 0;
    const SUBJECT_SIZE = 12;
    const STD_SIZE = 9;
    const ITEM_SIZE = 8;
    const SUM_SIZE = 11;
    const META_DATA_MARGIN_LEFT  = 118.5;
    const META_DATA_VALUE_MARGIN_LEFT = 40;
    const READ_ON_MARGIN_LEFT = 118.5;
    const FONT = 'pdfahelvetica';
    const FONT_BOLD = 'pdfahelveticab';
    
    public function __construct(
        $orientation = 'P',
        $unit = 'mm',
        $format = 'A4',
        $unicode = true,
        $encoding = 'UTF-8',
        $diskCache = false,
        $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskCache, $pdfa);
        $this->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->setFontSubsetting(true);
    }

    public function Header() {

    }

    public function Footer() {

    }

    public function setAddress(AddressInterface $address)
    {
        $this->setCellMargins("", "", "", "");
        $this->SetFont(self::FONT);
        $this->SetFont(self::FONT_BOLD);

        if($address->getCompany()) {
            $this->MultiCell(self::ADDRESS_WIDTH, 0, $address->getCompany(), 0, "L", false, 1, self::MARGIN_LEFT, self::MARGIN_TOP);
            $this->MultiCell(self::ADDRESS_WIDTH, 0, $address->getFirstName(), 0, "L", false, 1, self::MARGIN_LEFT);
        } else {
            $this->MultiCell(self::ADDRESS_WIDTH, 0, $address->getFirstName(),0,"L",false,1,self::MARGIN_LEFT, self::MARGIN_TOP);
        }

        $this->MultiCell(self::ADDRESS_WIDTH, 0, $address->getStreet(), 0, "L", false, 1 ,self::MARGIN_LEFT);
        $this->MultiCell(self::ADDRESS_WIDTH, 0, $address->getPostcode(), 0, "L", false, 1, self::MARGIN_LEFT);

        $country = $address->getCountryCode();
        $this->MultiCell(self::ADDRESS_WIDTH, 0, $country, 0, "L", false, 1, self::MARGIN_LEFT);
        if(!$address->getCompany()) {
            $this->MultiCell(self::ADDRESS_WIDTH, 0, "", 0, "L", false, 1, self::MARGIN_LEFT);
        }
    }

    protected function getMetaDataMarginLeft()
    {
        return self::MARGIN_LEFT + self::META_DATA_MARGIN_LEFT;
    }

    protected function getMetaDataValueMarginLeft()
    {
        return $this->getMetaDataMarginLeft() + self::META_DATA_VALUE_MARGIN_LEFT;
    }

    protected function getReadOnMarginLeft()
    {
        return self::MARGIN_LEFT + self::READ_ON_MARGIN_LEFT;
    }
}