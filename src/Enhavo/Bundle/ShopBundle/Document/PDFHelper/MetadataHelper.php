<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Enhavo\Bundle\ShopBundle\Document\PDF;

class MetadataHelper implements PDFHelperInterface
{
    use PDFHelperTrait;

    protected array $lines = [];

    public function render(PDF $pdf, $options = [])
    {
        $this->reset($pdf, $options);

        $pdf->setCellMargins("", "", "", "");

        $first = true;
        foreach ($this->lines as $line) {
            $this->setStyle($pdf, $options, $line['style']);
            if ($first) {
                $pdf->MultiCell($options['meta_data_width'], 0, sprintf("%s:", $line['key']), 0, "L", false, 1, $options['meta_data_margin_left'], $options['meta_data_margin_top']);
                $first = false;
                continue;
            }
            $pdf->MultiCell($options['meta_data_width'], 0, sprintf("%s:", $line['key']), 0, "L", false, 1, $options['meta_data_margin_left']);
        }

        $first = true;
        foreach ($this->lines as $line) {
            $this->setStyle($pdf, $options, $line['style']);
            if ($first) {
                $pdf->MultiCell($options['meta_data_value_width'], 0, $line['value'], 0, "L", false, 1, $options['meta_data_margin_left']+$options['meta_data_value_width'], $options['meta_data_margin_top']);
                $first = false;
                continue;
            }
            $pdf->MultiCell($options['meta_data_value_width'], 0, $line['value'], 0, "L", false, 1, $options['meta_data_margin_left']+$options['meta_data_value_width']);
        }
    }

    public function addLine($key, $value, $style = null)
    {
        $this->lines[] = [
            'key' => $key,
            'value' => $value,
            'style' => $style,
        ];
    }
}
