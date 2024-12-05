<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.09.17
 * Time: 23:26
 */

namespace Enhavo\Bundle\MediaBundle\Routing;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Repository\FormatRepository;
use Symfony\Component\Routing\Generator\UrlGenerator as SymfonyUrlGenerator;
use Symfony\Component\Routing\RouterInterface;

class ThemeUrlGenerator implements UrlGeneratorInterface
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly FormatRepository $formatRepository,
    )
    {
    }

    public function generate(FileInterface $file, $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH): string
    {
        return $this->router->generate('enhavo_media_file_show', [
            'token' => $file->getToken(),
            'shortChecksum' => $file->getShortChecksum(),
            'filename' => $file->getFilename()
        ], $referenceType);
    }

    public function generateFormat(FileInterface $file, string $format, $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH): string
    {
        /** @var FormatInterface $formatEntity */
        $formatEntity = $this->formatRepository->findOneBy([
            'file' => $file,
            'name' => $format
        ]);

        return $this->router->generate('enhavo_media_theme_format', [
            'token' => $file->getToken(),
            'shortChecksum' => $file->getShortChecksum(),
            'filename' => $file->getFilename(),
            'format' => $format,
            'extension' => $formatEntity->getExtension(),
        ], $referenceType);
    }
}
