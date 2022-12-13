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
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\AddressHelper;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\FurtherPageHelper;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\MetadataHelper;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\Style;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\SubjectHelper;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\SummaryHelper;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\TableHelper;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BillGenerator extends AbstractPDFGenerator
{
    public function __construct(
        FileFactory $fileFactory,
        private CurrencyFormatter $currencyFormatter,
        private ?string $backgroundImage,
        private KernelInterface $kernel,
        private TranslatorInterface $translator,
    )
    {
        parent::__construct($fileFactory);
    }

    public function generatePDF(PDF $pdf, OrderInterface $order, $options = [])
    {
        $items = $order->getItems();

        $pages = $this->getPages($items, $options);

        foreach ($pages as $pageNumber => $items) {
            $lastPage = $pageNumber === count($pages);
            $pdf->AddPage();

            $address = new AddressHelper($order->getBillingAddress());
            $address->render($pdf, $options);

            $subject = new SubjectHelper('Rechnung');
            $subject->render($pdf, $options);

            $metaData = new MetadataHelper();
            $metaData->addLine('Rechnungsnummer', $order->getNumber());
            $metaData->addLine('Rechnungsdatum', $order->getCheckoutCompletedAt()->format('d.m.Y'));
            if (count($pages) > 1) {
                $metaData->addLine('Seite', sprintf ('%s / %s', $pageNumber, count($pages)));
            }

            $metaData->render($pdf, $options);

            $tableHelper = new TableHelper([
                'Artikel' => 50,
                'Menge' => 8,
                'Betrag (Netto)' => 16,
                'USt.' => 12,
                'Betrag' => 10,
            ]);

            /** @var OrderItemInterface $item */
            foreach ($items as $item) {
                $tableHelper->addRow([
                    $item->getName(),
                    $item->getQuantity(),
                    $this->formatPrice($item->getDiscountedUnitPrice()),
                    $this->formatPrice($item->getTaxTotal()),
                    $this->formatPrice($item->getTotal()),
                ]);
            }
            $tableHelper->render($pdf, $options);

            if (count($pages) > 1 && !$lastPage) {
                (new FurtherPageHelper(sprintf('Fortsetzung auf Seite %s', $pageNumber+1)))->render($pdf, $options);
            } else if ($lastPage) {
                $summaryHelper = new SummaryHelper();
                $summaryHelper->addLine('Zwischensumme', $this->formatPrice($order->getItemsTotal(), 'Euro'));
                $summaryHelper->addLine('Versandkosten', $this->formatPrice($order->getShippingTotal(), 'Euro'));
                $summaryHelper->addLine('Mehrwertsteuer', $this->formatPrice($order->getTaxTotal(), 'Euro'));

                $summaryHelper->addLine('Rechnungsbetrag', $this->formatPrice($order->getTotal(), 'Euro'), Style::STYLE_HIGHLIGHT);
                $summaryHelper->render($pdf, $options);
            }
        }
    }

    private function getPages($items, $options): array
    {
        $pages = [];
        $pageNumber = 0;
        for ($i = 0; $i < count($items); $i++) {
            if ($i%$options['items_per_page'] === 0) {
                $pageNumber++;
                $pages[$pageNumber] = [];
            }

            $pages[$pageNumber][] = $items[$i];
        }
        return $pages;
    }

    protected function formatPrice($value, $currency = '€')
    {
        return $this->currencyFormatter->getCurrency($value, $currency);
    }

    protected function getFileName(OrderInterface $order, $options = [])
    {
        return sprintf('billing-%s.pdf', $order->getNumber());
    }
}
