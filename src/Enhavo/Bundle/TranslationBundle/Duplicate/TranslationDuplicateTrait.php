<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Duplicate;

use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateTypeInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;

/**
 * Copies the stored translations of the source property to the target property.
 * The default locale is skipped, because its value lives in the property itself.
 */
trait TranslationDuplicateTrait
{
    private TranslationManager $translationManager;

    /**
     * @param callable|null $transform receives the translated value and returns the value for the target
     */
    private function duplicateTranslations(SourceValue $sourceValue, TargetValue $targetValue, ?callable $transform = null): void
    {
        $source = $sourceValue->getParent();
        $target = $targetValue->getParent();
        $property = $sourceValue->getPropertyName();

        if (null === $target || null === $property || !$this->translationManager->isTranslatable($source, $property)) {
            return;
        }

        foreach ($this->translationManager->getLocales() as $locale) {
            if ($locale === $this->translationManager->getDefaultLocale()) {
                continue;
            }

            $value = $this->translationManager->getProperty($source, $property, $locale);
            // keep the target value if the source has none, e.g. when restoring a revision created without translations
            if (null === $value) {
                continue;
            }

            $this->translationManager->setTranslation($target, $property, $locale, $transform ? $transform($value) : $value);
        }
    }

    /**
     * Runs the extended type on a single translated value and returns its result.
     */
    private function duplicateWithType(DuplicateTypeInterface $type, $options, mixed $value, $context): mixed
    {
        $translationTarget = new TargetValue();
        $type->duplicate($options, new SourceValue($value), $translationTarget, $context);

        return $translationTarget->getValue();
    }
}
