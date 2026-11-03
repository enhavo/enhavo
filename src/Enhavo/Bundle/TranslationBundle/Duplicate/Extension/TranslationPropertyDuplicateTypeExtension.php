<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Duplicate\Extension;

use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Diff;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateTypeExtension;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\PropertyDuplicateType;
use Enhavo\Bundle\TranslationBundle\Duplicate\TranslationDuplicateTrait;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;

/**
 * Copies the translations of translatable properties duplicated by the property type.
 */
class TranslationPropertyDuplicateTypeExtension extends AbstractDuplicateTypeExtension
{
    use TranslationDuplicateTrait;

    public function __construct(
        TranslationManager $translationManager,
        private readonly PropertyDuplicateType $propertyDuplicateType,
    ) {
        $this->translationManager = $translationManager;
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $this->duplicateTranslations($sourceValue, $targetValue);
    }

    public function diff($options, SourceValue $oldValue, SourceValue $newValue, Diff $diff, $context): void
    {
        $this->diffTranslations($this->propertyDuplicateType, $options, $oldValue, $newValue, $diff, $context);
    }

    public static function getExtendedTypes(): array
    {
        return [PropertyDuplicateType::class];
    }
}
