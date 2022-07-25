<?php
/**
 * BillingGenerator.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Document\Generator;

use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\ShopBundle\Document\AbstractPDFGenerator;
use Enhavo\Bundle\ShopBundle\Document\PDF;
use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Sylius\Component\Taxation\Model\TaxRate;
use Symfony\Component\Intl\Countries;

class BillGenerator extends AbstractPDFGenerator
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
        if (!array_key_exists('enable_tax_19', $options)) {
            $options['enable_tax_19'] = false;
        }

        if (!array_key_exists('enable_tax_7', $options)) {
            $options['enable_tax_7'] = false;
        }

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


        for($pageNumber=1; $pageNumber <= $totalPageNumber; $pageNumber++) {

            $pdf->AddPage();

            $stdFont = "pdfahelvetica";
            $stdFontBold = "pdfahelveticab";

            if ($order->getState() == OrderInterface::STATE_CANCELLED) {
                $subject = "Stornierung zu Rechnung " . $order->getNumber();
            } else {
                $subject = "Rechnung";
            }

            if($pageNumber > 1) {
                $subject .= " Seite ".$pageNumber;
            }

            $pdf->SetAutoPageBreak(false, 0);

            if (isset($options['backgroundImage'])) {
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
            if($order->getState() == "cancelled") {
                $pdf->MultiCell($metaDataWidth,0,"Stornierungsnr.:",0,"L",false,1,$metaDataMarginLeft,$metaDataMarginTop);
            } else {
                $pdf->MultiCell($metaDataWidth,0,"Rechnungsnummer:",0,"L",false,1,$metaDataMarginLeft,$metaDataMarginTop);
            }
            $pdf->MultiCell($metaDataWidth,0,"Rechnungsdatum:",0,"L",false,1,$metaDataMarginLeft);
            $pdf->MultiCell($metaDataWidth,0,"Bezahlart:",0,"L",false,1,$metaDataMarginLeft);

            // metadata values
            if($order->getState() == "cancelled")  {
                $pdf->MultiCell($metaDataValueWidth,0,"S" . $order->getNumber(),0,"L",false,1,$metaDataValueMarginLeft,$metaDataMarginTop);
            } else {
                $pdf->MultiCell($metaDataValueWidth,0,$order->getNumber(),0,"L",false,1,$metaDataValueMarginLeft,$metaDataMarginTop);
            }

            $pdf->MultiCell($metaDataWidth,0,$order->getCreatedAt()->format('d.m.Y'),0,"L",false,1,$metaDataValueMarginLeft);
            //$pdf->MultiCell($metaDataWidth,0,$order->getPayment()->getMethod()->getName(),0,"L",false,1,$metaDataValueMarginLeft);
            $pdf->MultiCell($metaDataWidth,0, '',0,"L",false,1,$metaDataValueMarginLeft);


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
            for ($i = $start; $i <= $end; $i++) {
                if(!isset($items[$i-1])) {
                    break;
                }
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
                //$pdf->Cell(25,0, ($product->getTaxRate()->getAmount()*100)."%",$cellBorder);
                $pdf->Cell(25,0, "%", $cellBorder);
                $pdf->Cell(33,0,$itemSum, $cellBorderLast);
            }

            if($totalPageNumber > 1 && $pageNumber < $totalPageNumber) {
                $pdf->Ln();
                $pdf->Ln();
                $pdf->Ln();
                $pdf->Ln();
                $pdf->MultiCell($metaDataWidth,0,"Fortsetzung auf Seite ".($pageNumber+1),0,"L",false,1,$readonMarginLeft);
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
        $pdf->MultiCell($sumWidth,0,"Zwischensumme:",0,"R",false,1,$sumMarginLeft);
        $pdf->MultiCell($sumWidth,0,"Versandkosten:",0,"R",false,1,$sumMarginLeft);

        if(isset($options['enable_tax_19']) && $options['enable_tax_19']) {
            $pdf->MultiCell($sumWidth, 0, "19% gesetzl. USt.", 0, "R", false, 1, $sumMarginLeft);
        }

        if(isset($options['enable_tax_7']) && $options['enable_tax_7']) {
            $pdf->MultiCell($sumWidth,0,"7% gesetzl. USt.",0,"R",false,1,$sumMarginLeft);
        }

        if($order->getDiscountTotal() != 0) {
            $pdf->MultiCell($sumWidth,0,"Ermäßigt:",0,"R",false,1,$sumMarginLeft);
        }

        $pdf->SetFont($stdFontBold);
        $pdf->SetFontSize($sumSize);
        $pdf->MultiCell($sumWidth,0,"Rechnungsbetrag:",0,"R",false,1,$sumMarginLeft);

        $shippingNetSum = $this->currencyFormatter->getCurrency($order->getShippingTotal());
        $netSum = $this->currencyFormatter->getCurrency($order->getUnitTotal());
        // not available in order?
        $totalSum = $this->currencyFormatter->getCurrency($order->getTotal());

        $pdf->SetFont($stdFont);
        $pdf->SetFontSize($stdSize);
        $pdf->SetAbsY($y);
        $pdf->MultiCell($sumValueWidth,0,$netSum,0,"L",false,1,$sumValueMarginLeft);
        $pdf->MultiCell($sumValueWidth,0,$shippingNetSum,0,"L",false,1,$sumValueMarginLeft);



        if(isset($options['enable_tax_19']) && $options['enable_tax_19']) {
            $taxValue = $this->currencyFormatter->getCurrency($this->getTaxByCode($order, '19', true));
            $pdf->MultiCell($sumValueWidth, 0, $taxValue, 0, "L", false, 1, $sumValueMarginLeft);
        }

        if(isset($options['enable_tax_7']) && $options['enable_tax_7']) {
            $taxValue = $this->currencyFormatter->getCurrency($this->getTaxByCode($order, '7', true));
            $pdf->MultiCell($sumValueWidth, 0, $taxValue, 0, "L", false, 1, $sumValueMarginLeft);
        }

        if($order->getDiscountTotal() != 0) {
            $taxValue = $this->currencyFormatter->getCurrency($order->getDiscountTotal());
            $pdf->MultiCell($sumValueWidth, 0, $taxValue, 0, "L", false, 1, $sumValueMarginLeft);
        }

        $pdf->SetFont($stdFontBold);
        $pdf->SetFontSize($sumSize);
        $pdf->MultiCell($sumValueWidth,0,$totalSum,0,"L",false,1,$sumValueMarginLeft);
    }

    public function getTaxByCode(OrderInterface $order, $code)
    {
        $taxRate = $this->container->get('sylius.repository.tax_rate')->findOneBy([
            'code' => $code,
        ]);
        /** @var AdjustmentInterface[] $adjustments */
        $adjustments = $order->getAdjustmentsRecursively(AdjustmentInterface::TAX_ADJUSTMENT);
        $amount = 0;
        foreach($adjustments as $adjustment) {
            if ($adjustment->getOriginType() === TaxRate::class && $adjustment->getOriginId() == $taxRate->getId()) {
                $amount += $adjustment->getAmount();
            }
        }
        return $amount;
    }

    protected function getFileName(OrderInterface $order, $options = [])
    {
        return sprintf('billing-%s.pdf', $order->getNumber());
    }
}
