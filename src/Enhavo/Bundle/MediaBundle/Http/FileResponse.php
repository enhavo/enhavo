<?php

namespace Enhavo\Bundle\MediaBundle\Http;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\HttpFoundation\Response;

class FileResponse extends Response
{
    const DISPOSITION_INLINE = 'inline';
    const DISPOSITION_ATTACHMENT = 'attachment';

    public function __construct(FileInterface $file = null, $disposition = self::DISPOSITION_INLINE)
    {
        parent::__construct();
        $this->setFile($file, $disposition);
    }

    public function setFile(FileInterface $file, $disposition = self::DISPOSITION_INLINE)
    {
        $this->setContent($file->getContent()->getContent());
        $this->headers->set('Content-Type', $file->getMimeType());
        $this->headers->set('Content-Disposition', sprintf('%s; filename="%s"', $disposition, $file->getFilename()));
    }
}
