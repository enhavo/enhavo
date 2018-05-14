<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 31.05.16
 * Time: 12:01
 */

namespace Enhavo\Bundle\SearchBundle\Util;

use Enhavo\Bundle\SearchBundle\Extractor\Extractor;

/*
 * This class highlights a given resource
 */
class Highlighter
{
    /**
     * @var Extractor
     */
    private $extractor;

    public function __construct(Extractor $extractor)
    {
        $this->extractor = $extractor;
    }

    public function highlight($resource, array $words)
    {
        $text = $this->extractor->extract($resource);
        $text = implode("\n", $text);
        return $this->highlightText($text, $words);
    }

    protected function highlightText($text, array $words)
    {
        return $text;
    }
}