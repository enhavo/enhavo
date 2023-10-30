<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.09.17
 * Time: 23:26
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;

class PublicUrlGenerator implements UrlGeneratorInterface
{
    public function __construct(
        private readonly RouterInterface $router,
    )
    {
    }

    public function generate(FileInterface $file, $referenceType = UrlGenerator::ABSOLUTE_PATH): string
    {
        return $this->router->generate('enhavo_media_file_show', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename()
        ], $referenceType);
    }

    public function generateFormat(FileInterface $file, string $format, $referenceType = UrlGenerator::ABSOLUTE_PATH): string
    {
        return $this->router->generate('enhavo_media_file_format', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename(),
            'format' => $format,
        ], $referenceType);
    }
}
