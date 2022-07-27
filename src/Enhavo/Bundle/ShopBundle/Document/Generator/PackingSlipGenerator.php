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
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\AddressHelper;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\FurtherPageHelper;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\MetadataHelper;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\Style;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\SubjectHelper;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\SummaryHelper;
use Enhavo\Bundle\ShopBundle\Document\PDFHelper\TableHelper;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
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
        $items = $order->getItems();

        $pages = $this->getPages($items, $options);

        foreach ($pages as $pageNumber => $items) {
            $lastPage = $pageNumber === count($pages);
            $pdf->AddPage();

            $address = new AddressHelper($order->getShippingAddress());
            $address->render($pdf, $options);

            $subject = new SubjectHelper('Lieferschein');
            $subject->render($pdf, $options);

            $metaData = new MetadataHelper();
            $metaData->addLine('Bestellnummer', $order->getNumber());
            $metaData->addLine('Bestelldatum', $order->getCheckoutCompletedAt()->format('d.m.Y'));
            if (count($pages) > 1) {
                $metaData->addLine('Seite', sprintf ('%s / %s', $pageNumber, count($pages)));
            }

            $metaData->render($pdf, $options);

            $tableHelper = new TableHelper([
                'Artikel' => 50,
                'Menge' => 20,
            ]);

            /** @var OrderItemInterface $item */
            foreach ($items as $item) {
                $tableHelper->addRow([
                    $item->getName(),
                    $item->getQuantity()
                ]);
            }
            $tableHelper->render($pdf, $options);

            if (count($pages) > 1 && !$lastPage) {
                (new FurtherPageHelper(sprintf('Fortsetzung auf Seite %s', $pageNumber+1)))->render($pdf, $options);
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

    protected function getFileName(OrderInterface $order, $options = [])
    {
        return sprintf('packing-slip-%s.pdf', $order->getNumber());
    }
}
