<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.05.18
 * Time: 10:47
 */

namespace Enhavo\Bundle\SearchBundle\Result;

use Enhavo\Bundle\SearchBundle\Util\Highlighter;

class ResultConverter
{
    /**
     * @var Highlighter
     */
    private $highlighter;

    public function __construct(Highlighter $highlighter)
    {
        $this->highlighter = $highlighter;
    }

    public function convert(array $result)
    {
        $data = [];
        foreach($result as $resultItem) {
            $resultData = new Result();
            $text = $this->highlighter->highlight($resultItem, []);
            $resultData->setText($text);
            $resultData->getTitle($resultItem->getTitle());
            $resultData->setSubject($resultItem);
            $data[] = $resultData;
        }
        return $data;
    }
}