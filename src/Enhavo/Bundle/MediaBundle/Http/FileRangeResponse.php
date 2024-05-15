<?php

namespace Enhavo\Bundle\MediaBundle\Http;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\HttpFoundation\Response;

class FileRangeResponse extends Response
{

    public function __construct(FileInterface $file, int $length, int $start, int $end)
    {
        parent::__construct();
        $this->setStatusCode(Response::HTTP_PARTIAL_CONTENT);
        $this->setFile($file, $length, $start, $end);
    }

    public function setFile(FileInterface $file, int $length, int $start, int $end): void
    {
        $handle = fopen($file->getContent()->getFilePath(), 'rb');
        // Seek to the starting position and read the specified length
        fseek($handle, $start);
        $content = fread($handle, $length);
        fclose($handle);

        $this->setContent($content);
        $this->headers->set('Content-Range', sprintf('bytes %d-%d/%d', $start, $end, $length));
        $this->headers->set('Accept-Ranges', '0-' . $length);
        $this->headers->set('Content-Length', $length);
        $this->headers->set('Content-Type', $file->getMimeType());
    }
}
