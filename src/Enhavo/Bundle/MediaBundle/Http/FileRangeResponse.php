<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Http;

use Enhavo\Bundle\MediaBundle\Model\FileContentInterface;
use Symfony\Component\HttpFoundation\Response;

class FileRangeResponse extends Response
{
    public function __construct(FileContentInterface $file, int $start, int $end)
    {
        parent::__construct();
        $this->setStatusCode(Response::HTTP_PARTIAL_CONTENT);
        $this->setFile($file, $start, $end);
    }

    public function setFile(FileContentInterface $file, int $start, int $end): void
    {
        $path = $file->getContent()->getFilePath();
        $size = filesize($path);

        // Adjust if range is beyond the file size
        if ($end >= $size) {
            $end = $size - 1;
        }
        $length = $end - $start + 1;

        $handle = fopen($path, 'rb');
        // Seek to the starting position and read the specified length
        fseek($handle, $start);
        $content = fread($handle, $length);
        fclose($handle);

        $this->setContent($content);
        $this->headers->set('Content-Range', sprintf('bytes %d-%d/%d', $start, $end, $size));
        $this->headers->set('Accept-Ranges', '0-'.$length);
        $this->headers->set('Content-Length', $length);
        $this->headers->set('Content-Type', $file->getMimeType());
    }
}
