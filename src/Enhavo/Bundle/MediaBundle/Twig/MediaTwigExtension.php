<?php
/**
 * MediaExtension.php
 *
 * @since 07/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Twig;

use Enhavo\Bundle\AppBundle\Twig\TwigRouter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MediaTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly TwigRouter $twigRouter,
    )
    {
    }

    public function getFunctions(): array
    {
        return array(
            new TwigFunction('media_url', array($this, 'getMediaUrl')),
            new TwigFunction('media_filename', array($this, 'getMediaFilename')),
            new TwigFunction('media_parameter', array($this, 'getMediaParameter')),
            new TwigFunction('media_is_picture', array($this, 'isPicture')),
        );
    }

    public function getMediaUrl(?array $file, $format = null, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        if ($file === null) {
            return null;
        } else if ($format !== null) {
            return $this->twigRouter->generate('enhavo_media_theme_format', [
                'token' => $file['token'],
                'shortChecksum' => $file['shortChecksum'],
                'filename' => $file['filename'],
                'extension' => $file['extension'],
                'format' => $format,
            ], $referenceType);
        } else {
            return $this->twigRouter->generate('enhavo_media_theme_file', [
                'token' => $file['token'],
                'shortChecksum' => $file['shortChecksum'],
                'filename' => $file['filename'],
                'extension' => $file['extension'],
            ], $referenceType);
        }
    }

    public function getMediaFilename(?array $file): ?string
    {
        if ($file === null) {
            return null;
        }

        return $file['filename'];
    }

    public function getMediaParameter(?array $file, $parameterName): ?string
    {
        if ($file === null) {
            return null;
        }

        if (isset($file['parameters'][$parameterName])) {
            return $file['parameters'][$parameterName];
        }

        return null;
    }

    public function isPicture(?array $file): bool
    {
        if ($file === null) {
            return false;
        }

        if (in_array($file['extension'], ['jpg', 'jpeg', 'png', 'gif'])) {
            return true;
        }

        return false;
    }
}
