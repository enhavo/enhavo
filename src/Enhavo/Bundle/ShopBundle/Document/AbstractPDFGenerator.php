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
            'margin_left' => 22,
            'margin_top' => 54,
            'address_width' => 60,
            'meta_data_width' => 40,
            'meta_data_value_width' => 25,
            'subject_width' => 80,
            'sum_width' => 60,
            'sum_value_width' => 40,
            'meta_data_margin_top' => 79,
            'subject_margin_top' => 108,
            'item_table_margin_top' => 118,
            'sum_margin_left' => 100,
            'sum_value_margin_left' => 167,
            'std_color' => 0,
            'subject_size' => 12,
            'std_size' => 9,
            'item_size' => 8,
            'sum_size' => 11,
            'meta_data_margin_left'  => 118.5,
            'meta_data_value_margin_left' => 40,
            'read_on_margin_left' => 118.5,
            'font' => 'pdfahelvetica',
            'font_bold' => 'pdfahelveticab',
        ]);
    }

    abstract protected function generatePDF(PDF $pdf, OrderInterface $order, $options);

    abstract protected function getFileName(OrderInterface $order, $options);
}
