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
        $translation = $this->container->get('enhavo_translation.repository.translation_string')->findOneBy(array('translationKey' => $key));
        if($translation === null) {
            return $key;
        }
        return $translation->getTranslationValue();
    }

    public function getName()
    {
        return 'translate_string';
    }
}