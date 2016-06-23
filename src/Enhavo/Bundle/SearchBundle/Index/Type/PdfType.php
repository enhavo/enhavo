<?php
namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Enhavo\Bundle\SearchBundle\Index\IndexEngine;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\SearchBundle\Index\AbstractIndexType;
use Enhavo\Bundle\MediaBundle\Service\FileService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:16
 */

class PdfType extends AbstractIndexType
{
    protected $util;
    protected $indexEngine;
    protected $fileService;

    public function __construct(SearchUtil $util, IndexEngine $indexEngine, FileService $fileService)
    {
        $this->util = $util;
        $this->indexEngine = $indexEngine;
        $this->fileService = $fileService;
    }
    function index($value, $options)
    {
        $minimumWordSize = $this->getMinimumWordSize($options);
        if($value instanceof FileInterface) {
            $pdfContent = $this->getPdfContent($value);
            $options = array(
                'weight' => $options['weight'],
                'minimumWordSize' => $minimumWordSize
            );
            $plainType = new PlainType($this->util, $this->indexEngine);
            $scoredWords = $plainType->index($pdfContent, $options);
            $this->addWordsToSearchIndex($scoredWords, $options['dataSet'], $options['type']);
        }
    }

    public function getPdfContent($file)
    {
        if($file != null) {
            if (strpos($file->getMimeType(), 'pdf') !== false) {
                $response = new BinaryFileResponse($this->fileService->getFilepath($file));
                $text = $this->pdfToString($response->getFile());
                return $text;
            }
        }
        return '';
    }

    protected function pdfToString($sourcefile)
    {
        // Parse pdf file and build necessary objects.
        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($sourcefile);

        $text = $pdf->getText();
        $text = str_replace('&', ' ', $text);
        return $text;
    }

    /**
     * Returns a unique type name for this type
     *
     * @return string
     */
    public function getType()
    {
        return 'PDF';
    }

}