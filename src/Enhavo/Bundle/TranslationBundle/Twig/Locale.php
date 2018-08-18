<?php
/**
 * Locale.php
 *
 * @since 12/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Twig;

use Enhavo\Bundle\AppBundle\Routing\Routeable;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Locale extends \Twig_Extension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('resolve_locale', array($this, 'resolveLocale')),
            new \Twig_SimpleFunction('resolve_route', array($this, 'resolveRoute'))
        );
    }

    public function resolveLocale()
    {
        return $this->container->get('enhavo_translation.locale_resolver')->getLocale();
    }

    public function resolveRoute(Routeable $routeable)
    {
        return $this->container->get('enhavo_translation.translation_route_manager')->getRoute(
            $routeable,
            $this->resolveLocale()
        );
    }

    public function getName()
    {
        return 'enhavo_locale';
    }
}