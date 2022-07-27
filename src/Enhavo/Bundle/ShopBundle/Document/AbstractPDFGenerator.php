<?php

namespace Enhavo\Bundle\ShopBundle\Document;

use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractPDFGenerator implements GeneratorInterface
{
    public function __construct(
        private FileFactory $fileFactory
    )
    {
    }

    public function generate(OrderInterface $order, $options = []): FileInterface
    {
        $pdf = new PDF();
        $this->generatePDF($pdf, $order, $options);

        $file = $this->fileFactory->createFromContent($pdf->Output(null, 'S'));

        $file->setMimeType('application/pdf');
        $file->setFilename($this->getFileName($order, $options));
        return $file;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'font' => 'pdfahelvetica',
            'font_bold' => 'pdfahelveticab',
            'font_color' => 0,
            'font_size' => 9,
            'font_highlight_size' => 11,
            'font_small_size' => 8,
            'font_header_size' => 12,

            'item_table_margin_top' => 100,
            'items_per_page' => 15,
            'item_table_width' => 172,

            'subject_width' => 80,
            'subject_margin_top' => 90,

            'margin_left' => 22,

            'meta_data_width' => 40,
            'meta_data_margin_top' => 54,
            'meta_data_margin_left'  => 118.5,
            'meta_data_value_width' => 45,
            'meta_data_value_margin_left' => 40,

            'address_width' => 60,
            'address_margin_top' => 54,

            'sum_width' => 60,
            'sum_value_width' => 40,
            'sum_margin_left' => 100,
            'sum_value_margin_left' => 167,

            'further_page_margin_left' => 160,
            'further_page_width' => 40,
        ]);
    }

    abstract protected function generatePDF(PDF $pdf, OrderInterface $order, $options);

    abstract protected function getFileName(OrderInterface $order, $options);
}
