<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Routing;

use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\Routing\Generator\UrlGenerator as SymfonyUrlGenerator;
use Symfony\Component\Routing\RouterInterface;

class ThemeUrlGenerator implements UrlGeneratorInterface
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly FormatManager $formatManager,
    ) {
    }

    public function generate(FileInterface $file, $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH): string
    {
        return $this->router->generate('enhavo_media_theme_file', [
            'token' => $file->getToken(),
            'shortChecksum' => $file->getShortChecksum(),
            'filename' => $file->getFilename(),
            'extension' => $file->getExtension(),
        ], $referenceType);
    }

    public function generateFormat(FileInterface $file, string $format, $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH): string
    {
        $formatEntity = $this->formatManager->getFormat($file, $format);

        return $this->router->generate('enhavo_media_theme_format', [
            'token' => $file->getToken(),
            'shortChecksum' => $file->getShortChecksum(),
            'filename' => $file->getFilename(),
            'format' => $format,
            'extension' => $formatEntity->getExtension(),
        ], $referenceType);
    }
}
