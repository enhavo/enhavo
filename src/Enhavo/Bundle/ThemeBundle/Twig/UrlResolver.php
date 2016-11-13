<?php
/**
 * UrlResolver.php
 *
 * @since 12/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ThemeBundle\Twig;

use Enhavo\Bundle\AppBundle\Twig\UrlResolver as AppUrlResolver;

class UrlResolver extends AppUrlResolver
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('resolve_theme_url', array($this, 'resolveUrl')),
        );
    }

    public function getName()
    {
        return 'resolve_theme_url';
    }
}