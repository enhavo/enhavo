<?php
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 12.12.16
 * Time: 15:39
 */

namespace Enhavo\Bundle\ShopBundle\Document\Generator;

use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\ShopBundle\Document\AbstractPDFGenerator;
use Enhavo\Bundle\ShopBundle\Document\PDF;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Symfony\Component\Intl\Countries;

class PackingSlipGenerator extends AbstractPDFGenerator
{
    public function __construct(
        FileFactory $fileFactory,
        private CurrencyFormatter $currencyFormatter,
        private ?string $backgroundImage,
    )
    {
        parent::__construct($fileFactory);
    }

    public function generatePDF(PDF $pdf, OrderInterface $order, $options = [])
    {
        $pdf->SetTitle("Lieferschein");

        /**
         * @var OrderItem[] $items
         */
        $items = $order->getItems();

        $itemsPerPage = 15;
        $totalPageNumber = ceil(count($items)/$itemsPerPage);

        $marginLeft = 22;
        $marginTop = 54;

        $addressWidth = 60;
        $metaDataWidth = 40;
        $metaDataValueWidth = 25;
        $subjectWidth = 80;
        $sumWidth = 60;
        $sumValueWidth = 40;

        $metaDataMarginTop = 79;
        $subjectMarginTop = 108;
        $metaDataMarginLeft = $marginLeft+118.5;
        $metaDataValueMarginLeft = $metaDataMarginLeft+40;
        $readonMarginLeft = $marginLeft+118.5;
        $itemTableMarginTop = 118;
        $sumMarginLeft = 100;
        $sumValueMarginLeft = 167;

        $stdColor = 0;

        $subjectSize = 12;
        $stdSize = 9;
        $itemSize = 8;
        $sumSize = 11;

        for($pageNumber=1 ; $pageNumber <= $totalPageNumber; $pageNumber++) {

            $pdf->AddPage();

            $stdFont = "pdfahelvetica";
            $stdFontBold = "pdfahelveticab";

            if($order->getState() == "cancelled") {
                $subject = "Stornierung zu Rechnung " . $order->getNumber();
            } else {
                $subject = "Lieferschein";
            }


            if($pageNumber > 1) {
                $subject .= " Seite ".$pageNumber;
            }

            $pdf->SetAutoPageBreak(false, 0);

            if(isset($options['backgroundImage'])) {
                $backgroundImagePath = $this->container->get('kernel')->locateResource($options['backgroundImage']);
                $pdf->Image($backgroundImagePath,0,0,210,297,'','','T',true);
            }

            $pdf->SetFontSize($stdSize);
            $pdf->SetTextColor($stdColor);

            // billing address
            $pdf->setCellMargins("","","","");
            $pdf->SetFont($stdFontBold);
            $pdf->SetFont($stdFont);
            if($order->getBillingAddress()->getCompany()) {
                $pdf->MultiCell($addressWidth,0,$order->getBillingAddress()->getCompany(),0,"L",false,1,$marginLeft,$marginTop);
                $pdf->MultiCell($addressWidth,0,$order->getBillingAddress()->getFirstName()." ".$order->getBillingAddress()->getLastName(),0,"L",false,1,$marginLeft);
            } else {
                $pdf->MultiCell($addressWidth,0,$order->getBillingAddress()->getFirstName()." ".$order->getBillingAddress()->getLastName(),0,"L",false,1,$marginLeft,$marginTop);
            }

            $pdf->MultiCell($addressWidth,0,$order->getBillingAddress()->getStreet(),0,"L",false,1,$marginLeft);
            $pdf->MultiCell($addressWidth,0,$order->getBillingAddress()->getPostcode().' '.$order->getBillingAddress()->getCity(),0,"L",false,1,$marginLeft);
            $pdf->MultiCell($addressWidth,0,Countries::getName($order->getBillingAddress()->getCountryCode()),0,"L",false,1,$marginLeft);
            if(!$order->getBillingAddress()->getCompany()) {
                $pdf->MultiCell($addressWidth,0,"",0,"L",false,1,$marginLeft);
            }

            // subject
            $pdf->SetFont($stdFontBold);
            $pdf->SetFontSize($subjectSize);
            $pdf->MultiCell($subjectWidth,0,$subject,0,"L",false,1,$marginLeft,$subjectMarginTop);

            // metadata
            $pdf->SetFont($stdFont);
            $pdf->SetFontSize($stdSize);

            $pdf->MultiCell($metaDataWidth,0,"Bestellnummer:",0,"L",false,1,$metaDataMarginLeft,$metaDataMarginTop);

            $pdf->MultiCell($metaDataValueWidth,0, $order->getNumber(),0,"L",false,1,$metaDataValueMarginLeft,$metaDataMarginTop);


            // item table header
            $pdf->SetFontSize($itemSize);
            $pdf->SetFont($stdFontBold);
            $pdf->setCellPaddings(1.2,1,0,1);
            $pdf->SetAbsX($marginLeft);
            $pdf->SetAbsY($itemTableMarginTop);
            $pdf->Cell(64,0,"Artikel",'B', 0, 'L');
            $pdf->Cell(15,0,"Menge",'B', 0, 'L');
            $pdf->Cell(40,0,"Betrag netto",'B', 0, 'L');
            $pdf->Cell(25,0,"USt. %",'B', 0, 'L');
            $pdf->Cell(33,0,"Betrag (inkl. USt.)",'B', 0, 'L');


            $pdf->SetFont($stdFont);
            $pdf->setCellPaddings(1.5,1.5,0,1.5);

            $start = ($pageNumber-1)*$itemsPerPage+1;
            $end = $itemsPerPage*$pageNumber;
            for($i=$start;$i<=$end;$i++) {
                if(!isset($items[$i-1])) break;
                $orderedArticle = $items[$i-1];

                $itemSum = $this->currencyFormatter->getCurrency($orderedArticle->getUnitTotal());
                $itemNet = $this->currencyFormatter->getCurrency($orderedArticle->getUnitPrice());

                $pdf->Ln();
                $pdf->SetAbsX($marginLeft);

                if($i == $end || $i == count($items)) {
                    $cellBorder = "R";
                    $cellBorderLast = "";
                } else {
                    $cellBorder = "BR";
                    $cellBorderLast = "B";
                }
                // missing packing unit in name for merchants
                $pdf->Cell(64,0,$orderedArticle->getName(),$cellBorder);

                $pdf->Cell(15,0,$orderedArticle->getQuantity(),$cellBorder);
                $pdf->Cell(40,0,$itemNet,$cellBorder);
                //$pdf->Cell(25,0,($product->getTaxRate()->getAmount()*100)."%",$cellBorder);
                $pdf->Cell(25,0,"%",$cellBorder);
                $pdf->Cell(33,0,$itemSum,$cellBorderLast);
            }

            if($totalPageNumber > 1 && $pageNumber < $totalPageNumber) {
                $pdf->Ln();
                $pdf->Ln();
                $pdf->Ln();
                $pdf->Ln();
                $pdf->MultiCell($metaDataWidth,0, "Fortsetzung auf Seite". " " .($pageNumber+1),0,"L",false,1,$readonMarginLeft);
            }
            $pdf->setCellPaddings(0,0,0,0);
        }

        // sum
        $y = $pdf->GetY();
        $y += 18;
        $pdf->SetAbsY($y);

        $pdf->SetCellPadding(0);
        $pdf->setCellMargins("","","",0.5);
        $pdf->SetFontSize($stdSize);
    }

    protected function getFileName(OrderInterface $order, $options = [])
    {
        return sprintf('packing-slip-%s.pdf', $order->getNumber());
    }
}
