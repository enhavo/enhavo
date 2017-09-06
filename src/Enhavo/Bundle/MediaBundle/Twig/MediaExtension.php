<?php
/**
 * MediaExtension.php
 *
 * @since 07/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;

class MediaExtension extends \Twig_Extension
{
    /**
     * @var EngineInterface
     */
    protected $engine;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($container, $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('media_url', array($this, 'getMediaUrl')),
            new \Twig_SimpleFunction('media_filename', array($this, 'getMediaFilename')),
            new \Twig_SimpleFunction('media_parameter', array($this, 'getMediaParameter')),
            new \Twig_SimpleFunction('media_extension', array($this, 'getMediaExtension')),
            new \Twig_SimpleFunction('media_is_picture', array($this, 'isPicture')),
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
            return $this->container->get('enhavo_media.media.url_resolver')->getPublicFormatUrl($file, $format);
        } else {
            return $this->container->get('enhavo_media.media.url_resolver')->getPublicUrl($file);
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

    public function getName()
    {
        return 'enhavo_media_media_extension';
    }
} 
