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

class FileResponse extends Response
{
    public const DISPOSITION_INLINE = 'inline';
    public const DISPOSITION_ATTACHMENT = 'attachment';

    public function __construct(?FileContentInterface $file = null, $disposition = self::DISPOSITION_INLINE)
    {
        parent::__construct();
        $this->setFile($file, $disposition);
    }

    public function setFile(FileContentInterface $file, $disposition = self::DISPOSITION_INLINE): void
    {
        $this->setContent($file->getContent()->getContent());
        $this->headers->set('Content-Type', $file->getMimeType());

        if (self::DISPOSITION_ATTACHMENT === $disposition) {
            $this->headers->set('Content-Disposition', sprintf('%s; filename="%s"', $disposition, $file->getBasename()));
        } else {
            $this->headers->set('Content-Disposition', sprintf('%s', $disposition));
        }
        $this->headers->set('Content-Length', filesize($file->getContent()->getFilePath()));
        $this->headers->set('Content-Type', $file->getMimeType());
    }
}
