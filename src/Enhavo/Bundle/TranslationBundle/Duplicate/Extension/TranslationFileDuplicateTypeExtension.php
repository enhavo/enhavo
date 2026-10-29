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

use Enhavo\Bundle\MediaBundle\Duplicate\Type\FileDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateTypeExtension;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Enhavo\Bundle\TranslationBundle\Duplicate\TranslationDuplicateTrait;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;

/**
 * Duplicates the translated files of translatable properties duplicated by the file type.
 */
class TranslationFileDuplicateTypeExtension extends AbstractDuplicateTypeExtension
{
    use TranslationDuplicateTrait;

    public function __construct(
        TranslationManager $translationManager,
        private readonly FileDuplicateType $fileDuplicateType,
    ) {
        $this->translationManager = $translationManager;
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $this->duplicateTranslations($sourceValue, $targetValue, function ($value) use ($options, $context) {
            return $this->duplicateWithType($this->fileDuplicateType, $options, $value, $context);
        });
    }

    public static function getExtendedTypes(): array
    {
        return [FileDuplicateType::class];
    }
}
