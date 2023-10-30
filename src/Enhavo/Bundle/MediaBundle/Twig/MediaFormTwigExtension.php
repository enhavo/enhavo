<?php
/**
 * MediaExtension.php
 *
 * @since 07/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Twig;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MediaFormTwigExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return array(
            new TwigFunction('media_render_item', array($this, 'renderItem'), array('is_safe' => array('html'))),
            new TwigFunction('media_meta', array($this, 'getMeta')),
        );
    }
    public function renderItem($template, $form): string
    {
        return $this->container->get('twig')->render($template, [
            'form' => $form
        ]);
    }

    public function getMeta(FileInterface $file = null): ?array
    {
        if ($file !== null) {
            return [
                'id' => $file->getId(),
                'mimeType' => $file->getMimeType(),
                'extension' => $file->getExtension(),
                'order' => $file->getOrder(),
                'filename' => $file->getFilename(),
                'token' => $file->getToken(),
                'md5Checksum' => $file->getMd5Checksum(),
            ];
        }
        return null;
    }
}
