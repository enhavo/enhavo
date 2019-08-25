<?php
/**
 * TranslationTypeInterface.php
 *
 * @since 25/08/19
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface TranslationTypeInterface extends TypeInterface
{
    public function getFormType(array $options);

    public function setTranslation(array $options, $data, $property, $locale, $value);

    public function getTranslation(array $options, $data, $property, $locale);

    /**
     * @param $resolver OptionsResolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
