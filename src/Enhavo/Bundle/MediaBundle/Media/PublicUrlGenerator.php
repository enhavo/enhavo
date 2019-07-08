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
    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(MediaManager $mediaManager, RouterInterface $router)
    {
        $this->mediaManager = $mediaManager;
        $this->router = $router;
    }

    public function generate(FileInterface $file, $referenceType = UrlGenerator::ABSOLUTE_PATH)
    {
        return $this->router->generate('enhavo_media_file_show', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename()
        ], $referenceType);
    }

    public function generateFormat(FileInterface $file, string $format, $referenceType = UrlGenerator::ABSOLUTE_PATH)
    {
        $formatObj = $this->mediaManager->getFormat($file, $format);
        return $this->router->generate('enhavo_media_file_format', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $formatObj->getFilename(),
            'format' => $format,
        ], $referenceType);
    }
}
