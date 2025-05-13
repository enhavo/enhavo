<?php


namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author gseidel
 */
class LocaleExtension extends AbstractExtension
{
    /**
     * @var LocaleResolverInterface
     */
    private $localResolver;

    /**
     * LocaleExtension constructor.
     * @param LocaleResolverInterface $localResolver
     */
    public function __construct(LocaleResolverInterface $localResolver)
    {
        $this->localResolver = $localResolver;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('get_locale', array($this, 'getLocale')),
        );
    }

    public function getLocale()
    {
        return $this->localResolver->resolve();
    }
}
