<?php
namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Service\FileService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:16
 */

class PdfType extends PlainType
{
    protected $fileService;

    public function __construct(SearchUtil $util, ContainerInterface $container, FileService $fileService)
    {
        parent::__construct($util, $container);
        $this->fileService = $fileService;
    }

    function index($value, $options, $properties = null)
    {
        if($value instanceof FileInterface) {
            $pdfContent = $this->getPdfContent($value);
            return parent::index($pdfContent, $options, $properties);
        }
        return [];
    }

    public function getPdfContent(FileInterface $file)
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
        $text = str_replace(array('&', '%', '$'), ' ', $text);
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