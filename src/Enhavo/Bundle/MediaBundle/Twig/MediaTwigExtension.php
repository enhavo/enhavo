<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Twig;

use Enhavo\Bundle\AppBundle\Twig\TwigRouter;
use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MediaTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly TwigRouter $twigRouter,
        private readonly FormatManager $formatManager,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('media_url', [$this, 'getMediaUrl']),
            new TwigFunction('media_filename', [$this, 'getMediaFilename']),
            new TwigFunction('media_parameter', [$this, 'getMediaParameter']),
            new TwigFunction('media_is_picture', [$this, 'isPicture']),
        ];
    }

    public function getMediaUrl(?array $file, $format = null, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        if (null === $file) {
            return null;
        } elseif (null !== $format) {
            $extension = $this->formatManager->predictFormatExtension($file['extension'], $format);
            if (null === $extension) {
                $extension = $file['extension'];
            }

            return $this->twigRouter->generate('enhavo_media_theme_format', [
                'token' => $file['token'],
                'shortChecksum' => $file['shortChecksum'],
                'filename' => $file['filename'],
                'extension' => $extension,
                'format' => $format,
            ], $referenceType);
        }

        return $this->twigRouter->generate('enhavo_media_theme_file', [
            'token' => $file['token'],
            'shortChecksum' => $file['shortChecksum'],
            'filename' => $file['filename'],
            'extension' => $file['extension'],
        ], $referenceType);
    }

    public function getMediaFilename(?array $file): ?string
    {
        if (null === $file) {
            return null;
        }

        return $file['filename'];
    }

    public function getMediaParameter(?array $file, $parameterName): ?string
    {
        if (null === $file) {
            return null;
        }

        if (isset($file['parameters'][$parameterName])) {
            return $file['parameters'][$parameterName];
        }

        return null;
    }

    public function isPicture(?array $file): bool
    {
        if (null === $file) {
            return false;
        }

        if (in_array($file['extension'], ['jpg', 'jpeg', 'png', 'gif'])) {
            return true;
        }

        return false;
    }
}
