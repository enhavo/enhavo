<?php
/**
 * MediaExtension.php
 *
 * @since 07/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\MediaBundle\Twig;

use Symfony\Component\Templating\EngineInterface;
use esperanto\MediaBundle\Entity\File;

class MediaExtension extends \Twig_Extension
{
    protected $engine;
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('media_url', array($this, 'getMediaUrl')),
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

    public function getMediaUrl($file,$width=null,$height=null)
    {
        $path = '';
        if($file) {
            $path .= '/file/'.$file->getId();
        }
        if($width) {
            $path .= '/'.$width;
            if($height) {
                $path .= '/'.$height;
            }

        }

        return $path;
    }

    public function getName()
    {
        return 'esperanto_media_media_extension';
    }
} 