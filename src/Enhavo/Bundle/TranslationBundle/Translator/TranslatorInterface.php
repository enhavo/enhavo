<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

use Doctrine\ORM\EntityRepository;

interface TranslatorInterface
{
    public function setTranslation($entity, $property, $locale, $value): void;

    public function getTranslation($entity, $property, $locale);

    public function translate($entity, string $property, string $locale, array $options);

    public function getDefaultValue($entity, string $property);

    public function detach($entity, string $property, string $locale, array $options);

    public function delete($entity, string $property);

    public function getRepository(): EntityRepository;
}
