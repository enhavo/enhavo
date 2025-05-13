<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationTypeInterface;
use Enhavo\Component\Type\AbstractType;

class TranslationType extends AbstractType implements TranslationTypeInterface
{
    public function setTranslation(array $options, $data, string $property, string $locale, $value)
    {
    }

    public function getTranslation(array $options, $data, string $property, string $locale)
    {
        return null;
    }

    public function getDefaultValue(array $options, $data, string $property)
    {
        return null;
    }

    public function translate($object, string $property, string $locale, array $options): void
    {
    }

    public function detach($object, string $property, string $locale, array $options)
    {
    }

    public function delete($object, string $property): void
    {
    }
}
