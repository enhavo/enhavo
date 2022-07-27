<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Enhavo\Bundle\ShopBundle\Document\PDF;

class SummaryHelper implements PDFHelperInterface
{
    use PDFHelperTrait;

    private array $lines = [];

    public function addLine($key, $value, $style = Style::STYLE_NORMAL)
    {
        $this->lines[] = [
            'key' => $key,
            'value' => $value,
            'style' => $style,
        ];
    }

    public function render(PDF $pdf, $options = [])
    {
        $this->reset($pdf, $options);

        $y = $pdf->GetY();
        $y += 18;
        $pdf->SetAbsY($y);

        $pdf->SetCellPadding(0);
        $pdf->SetCellMargins("","","",0.5);

        foreach ($this->lines as $line) {
            $this->setStyle($pdf, $options, $line['style']);
            $pdf->MultiCell($options['sum_width'],0, sprintf('%s:', $line['key']),0,"R",false,1, $options['sum_margin_left']);
        }

        $pdf->SetAbsY($y);

        foreach ($this->lines as $line) {
            $this->setStyle($pdf, $options, $line['style']);
            $pdf->MultiCell($options['sum_value_width'],0, $line['value'],0,"L",false,1, $options['sum_value_margin_left']);
        }
    }
}
