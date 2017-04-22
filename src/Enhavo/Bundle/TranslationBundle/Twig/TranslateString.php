<?php
/**
 * TranslationString.php
 *
 * @since 28/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class TranslateString extends \Twig_Extension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('translate_string', array($this, 'getStringTranslation'))
        );
    }

    public function getStringTranslation($key)
    {
        return  $this->container->get('enhavo_translation.translate_string.translator')->translate($key);
    }

    public function getName()
    {
        return 'translate_string';
    }
}