<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.09.17
 * Time: 23:26
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\Routing\RouterInterface;

class UrlResolver
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

    public function getPublicUrl(FileInterface $file)
    {
        return $this->router->generate('enhavo_media_show', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename()
        ]);
    }
    
    public function getPrivateUrl(FileInterface $file)
    {
        return $this->router->generate('enhavo_media_resolve', [
            'token' => $file->getToken()
        ]);
    }

    public function getPublicFormatUrl(FileInterface $file, $format)
    {
        $formatObj = $this->mediaManager->getFormat($file, $format);
        return $this->router->generate('enhavo_media_format', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $formatObj->getFilename(),
            'format' => $format,
        ]);
    }

    public function getPrivateFormatUrl(FileInterface $file, $format)
    {
        $formatObj = $this->mediaManager->getFormat($file, $format);
        return $this->router->generate('enhavo_media_format', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $formatObj->getFilename(),
            'format' => $format,
        ]);
    }
}