<?php
/**
 * UrlResolver.php
 *
 * @since 12/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Route\UrlResolverInterface;

class UrlResolver extends \Twig_Extension
{
    /**
     * @var UrlResolverInterface
     */
    protected $urlResolver;

    /**
     * Template constructor.
     *
     * @param UrlResolverInterface $urlResolver
     */
    public function __construct(UrlResolverInterface $urlResolver)
    {
        $this->urlResolver = $urlResolver;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('resolve_url', array($this, 'resolveUrl')),
        );
    }

    /**
     * @return string
     */
    public function resolveUrl($resource)
    {
        return $this->urlResolver->resolve($resource);
    }

    public function getName()
    {
        return 'resolve_url';
    }
}