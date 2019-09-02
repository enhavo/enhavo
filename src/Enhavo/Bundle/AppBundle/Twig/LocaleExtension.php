<?php
/**
 * LocaleExtension.php
 *
 * @since 12/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

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
            new TwigFunction('get_locale', array($this, 'resolveLocale')),
        );
    }

    public function getLocale()
    {
        return $this->localResolver->resolve();
    }
}
