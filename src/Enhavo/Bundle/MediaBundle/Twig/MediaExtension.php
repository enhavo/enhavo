<?php
/**
 * MediaExtension.php
 *
 * @since 07/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace enhavo\MediaBundle\Twig;

use Symfony\Component\Templating\EngineInterface;
use enhavo\MediaBundle\Entity\File;

class MediaExtension extends \Twig_Extension
{
    protected $engine;
    protected $container;
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
            new \Twig_SimpleFunction('media_title', array($this, 'getMediaTitle')),
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
            $entityFile = $this->em->getRepository('enhavoMediaBundle:File')->find($file->getId());
            $path .= '/file/'.$file->getId();
        }
        if($width) {
            $path .= '/'.$width;
            if($height) {
                $path .= '/'.$height;
            }

        }

        if($file && $entityFile) {
            $filename = $entityFile->getFilename();
            if (!empty($filename)) {
                $path .= '/' . $entityFile->getFilename();
            }
        }

        return $path;
    }

    public function getMediaTitle($file)
    {

        $title = '';
        if($file) {
            $entityFile = $this->em->getRepository('enhavoMediaBundle:File')->find($file->getId());
            $title = $entityFile->getTitle();
        }

        return $title;
    }

    public function getName()
    {
        return 'enhavo_media_media_extension';
    }
} 
