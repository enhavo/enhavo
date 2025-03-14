<?php

namespace Enhavo\Bundle\MediaBundle\Http;

use Enhavo\Bundle\MediaBundle\Model\FileContentInterface;
use Symfony\Component\HttpFoundation\Response;

class FileResponse extends Response
{
    const DISPOSITION_INLINE = 'inline';
    const DISPOSITION_ATTACHMENT = 'attachment';

    public function __construct(FileContentInterface $file = null, $disposition = self::DISPOSITION_INLINE)
    {
        parent::__construct();
        $this->setFile($file, $disposition);
    }

    public function setFile(FileContentInterface $file, $disposition = self::DISPOSITION_INLINE): void
    {
        $this->setContent($file->getContent()->getContent());
        $this->headers->set('Content-Type', $file->getMimeType());

        if ($disposition === self::DISPOSITION_ATTACHMENT) {
            $this->headers->set('Content-Disposition', sprintf('%s; filename="%s"', $disposition, $file->getBasename()));
        } else {
            $this->headers->set('Content-Disposition', sprintf('%s', $disposition));
        }
        $this->headers->set('Content-Length', filesize($file->getContent()->getFilePath()));
        $this->headers->set('Content-Type', $file->getMimeType());
    }
}
