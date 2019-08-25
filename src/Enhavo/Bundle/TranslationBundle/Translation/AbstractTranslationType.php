<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:14
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractTranslationType implements TranslationTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getValidationConstraints(array $options, $data, $property, $locale)
    {
        return [];
    }
}
