<?php
namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:16
 *
 * Prepares fields of type pdf for indexing
 */

class PdfType extends PlainType
{
    public function __construct(SearchUtil $util, ContainerInterface $container)
    {
        parent::__construct($util, $container);
    }

    function index($value, $options, $properties = null)
    {
        //check if the given value is a file
        if($value instanceof FileInterface) {

            //get content if it it a pdf
            $pdfContent = $this->getPdfContent($value);

            //everything else does the parent of the pdfType
            return parent::index($pdfContent, $options, $properties);
        }
        return [];
    }

    public function getPdfContent(FileInterface $file)
    {
        return $this->pdfToString($file->getContent()->getContent());
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