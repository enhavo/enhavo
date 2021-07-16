<?php
/**
 * MediaExtension.php
 *
 * @since 07/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Twig;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Templating\EngineInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MediaExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * MediaExtension constructor.
     * @param $em
     * @param UrlGeneratorInterface $generator
     */
    public function __construct($em, UrlGeneratorInterface $generator)
    {
        $this->em = $em;
        $this->generator = $generator;
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

    public function getMediaUrl(?File $file, $format = null, $referenceType = UrlGenerator::ABSOLUTE_PATH)
    {
        if($file === null) {
            return '';
        }

        if($format) {
            return $this->generator->generateFormat($file, $format, $referenceType);
        } else {
            return $this->generator->generate($file, $referenceType);
        }
    }

    public function getMediaFilename(?File $file)
    {
        if($file === null) {
            return '';
        }

        return $file->getFilename();
    }

    public function getMediaParameter(?File $file, $parameterName)
    {
        if($file === null) {
            return '';
        }

        return $file->getParameter($parameterName);
    }

    public function getMediaExtension(?File $file)
    {
        if($file === null) {
            return '';
        }

        return strtolower($file->getExtension());
    }

    public function isPicture(?File $file)
    {
        if($file === null) {
            return false;
        }

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
