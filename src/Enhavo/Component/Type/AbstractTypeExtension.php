<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 10:56
 */

namespace Enhavo\Component\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractTypeExtension implements TypeExtensionInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {

    }
}
