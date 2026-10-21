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
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Diff;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;

/**
 * Copies the stored translations of the source property to the target property, and compares them in a diff.
 * The default locale is skipped, because its value lives in the property itself.
 *
 * Only for duplicate types extending AbstractType, the helpers use the parent type ($this->parent).
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

    /**
     * Compares the translations of every locale with the parent type, labels get the locale as suffix.
     */
    private function diffTranslations($options, SourceValue $oldValue, SourceValue $newValue, Diff $diff, $context): void
    {
        $old = $oldValue->getParent();
        $new = $newValue->getParent();
        $property = $newValue->getPropertyName() ?? $oldValue->getPropertyName();

        if (null === $property || !$this->translationManager->isTranslatable($new ?? $old, $property)) {
            return;
        }

        foreach ($this->translationManager->getLocales() as $locale) {
            if ($locale === $this->translationManager->getDefaultLocale()) {
                continue;
            }

            $oldTranslation = new SourceValue($old ? $this->translationManager->getProperty($old, $property, $locale) : null, $old, $property);
            $newTranslation = new SourceValue($new ? $this->translationManager->getProperty($new, $property, $locale) : null, $new, $property);

            $diff->withSuffix($locale, function () use ($options, $oldTranslation, $newTranslation, $diff, $context) {
                $this->parent->diff($options, $oldTranslation, $newTranslation, $diff, $context);
            });
        }
    }
}
