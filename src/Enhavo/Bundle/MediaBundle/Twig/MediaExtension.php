<?php
/**
 * MediaExtension.php
 *
 * @since 07/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Twig;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\MediaBundle\Media\UrlResolver;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MediaExtension extends AbstractExtension
{
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UrlResolver
     */
    private $resolver;

    public function __construct($container, $em, UrlResolver $resolver)
    {
        $this->container = $container;
        $this->em = $em;
        $this->resolver = $resolver;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('media_url', array($this, 'getMediaUrl')),
            new TwigFunction('media_filename', array($this, 'getMediaFilename')),
            new TwigFunction('media_parameter', array($this, 'getMediaParameter')),
            new TwigFunction('media_extension', array($this, 'getMediaExtension')),
            new TwigFunction('media_is_picture', array($this, 'isPicture')),
            new TwigFunction('media_render_item', array($this, 'renderItem'), array('is_safe' => array('html'))),
            new TwigFunction('media_meta', array($this, 'getMeta')),
        );
    }

    /**
     * @return EngineInterface
     */
    public function getEngine() {
        if($this->engine == null) {
            $this->engine = $this->container->get('templating');
        }
        return $this->engine;
    }

    public function getMediaUrl(File $file, $format = null)
    {
        if($format) {
            return $this->resolver->getPublicFormatUrl($file, $format);
        } else {
            return $this->resolver->getPublicUrl($file);
        }
    }

    public function getMediaFilename(File $file)
    {
        return $file->getFilename();
    }

    public function getMediaParameter(File $file, $parameterName)
    {
        return $file->getParameter($parameterName);
    }

    public function getMediaExtension(File $file)
    {
        return strtolower($file->getExtension());
    }

    public function isPicture(File $file)
    {
        if($this->getMediaExtension($file) == 'jpg' || $this->getMediaExtension($file) == 'jpeg' || $this->getMediaExtension($file) == 'png' || $this->getMediaExtension($file) == 'gif'){
            return true;
        }
        return false;
    }

    public function renderItem($template, $form)
    {
        return $this->container->get('templating')->render($template, [
            'form' => $form
        ]);
    }

    public function getMeta($file = null)
    {
        if ($file instanceof FileInterface) {
            $data = [
                'id' => $file->getId(),
                'mimeType' => $file->getMimeType(),
                'extension' => $file->getExtension(),
                'order' => $file->getOrder(),
                'filename' => $file->getFilename(),
                'token' => $file->getToken(),
                'md5Checksum' => $file->getMd5Checksum(),
            ];
            return $data;
        }
        return null;
    }
} 
