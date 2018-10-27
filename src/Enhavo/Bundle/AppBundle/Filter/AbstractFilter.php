<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 04.02.18
 * Time: 17:38
 */

namespace Enhavo\Bundle\AppBundle\Filter;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFilter extends AbstractType implements FilterInterface
{
    public function getPermission($options)
    {
        return $options['permission'];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'translation_domain' => null,
            'permission' => null
        ]);

        $optionsResolver->setRequired([
            'label',
            'property'
        ]);
    }
}