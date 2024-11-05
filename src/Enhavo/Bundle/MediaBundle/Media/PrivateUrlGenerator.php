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

class PrivateUrlGenerator implements UrlGeneratorInterface
{
    public function __construct(
        private readonly MediaManager $mediaManager,
        private readonly RouterInterface $router,
    )
    {
    }

    public function generate(FileInterface $file, $referenceType = UrlGenerator::ABSOLUTE_PATH): string
    {
        return $this->router->generate('enhavo_media_admin_api_file', [
            'token' => $file->getToken()
        ], $referenceType);
    }

    public function generateFormat(FileInterface $file, string $format, $referenceType = UrlGenerator::ABSOLUTE_PATH): string
    {
        $formatObj = $this->mediaManager->getFormat($file, $format);
        return $this->router->generate('enhavo_media_admin_api_file_format', [
            'token' => $formatObj->getFile()->getToken(),
            'format' => $format,
        ], $referenceType);
    }
}
