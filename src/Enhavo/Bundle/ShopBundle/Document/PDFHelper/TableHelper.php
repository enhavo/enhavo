<?php

namespace Enhavo\Bundle\ShopBundle\Document\PDFHelper;

use Enhavo\Bundle\ShopBundle\Document\PDF;

class TableHelper implements PDFHelperInterface
{
    use PDFHelperTrait;

    private array $rows = [];

    public function __construct(
        private array $headers,
    )
    {
    }

    public function addRow(array $rowData) {
        if (count($rowData) !== count($this->headers)) {
            throw new \InvalidArgumentException('Row data length is different from header length');
        }

        $this->rows[] = $rowData;
    }

    public function render(PDF $pdf, $options = [])
    {
        $this->reset($pdf, $options);

        $pdf->SetFontSize($options['font_size']);
        $pdf->SetFont($options['font_bold']);

        $pdf->SetCellPaddings(1.2,1,0,1);
        $pdf->SetAbsX($options['margin_left']);
        $pdf->SetAbsY($options['item_table_margin_top']);

        $widths = $this->getWidths($options['item_table_width']);

        $i = 0;
        foreach ($this->headers as $label => $width) {
            $pdf->Cell($widths[$i],0,$label,'B', 0, 'L');
            $i++;
        }

        $pdf->SetFont($options['font']);
        $pdf->SetCellPaddings(1.5,1.5,0,1.5);

        $this->setStyle($pdf, $options, Style::STYLE_SMALL);
        for ($i = 0; $i < count($this->rows); $i++) {
            $pdf->Ln();
            $pdf->SetAbsX($options['margin_left']);
            $lastRow = $i == count($this->rows) - 1;

            for ($l = 0; $l < count($this->rows[$i]); $l++) {
                $lastColumn = $l == count($this->rows[$i]) - 1;
                $pdf->Cell($widths[$l],0, $this->rows[$i][$l], $this->getBorder($lastRow, $lastColumn));
            }
        }
    }

    private function getWidths($length)
    {
        $data = [];
        $widths = array_values($this->headers);
        $sum = array_sum($widths);
        foreach ($widths as $width) {
            $data[] = intval(round(($width / $sum) * $length, 0));
        }
        return $data;
    }

    private function getBorder(bool $lastRow, bool $lastColumn)
    {
        if ($lastRow && $lastColumn) {
            return '';
        } elseif ($lastRow) {
            return 'R';
        } elseif ($lastColumn) {
            return 'B';
        }
        return 'BR';
    }
}
