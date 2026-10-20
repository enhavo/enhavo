<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Duplicate\Type;

use Enhavo\Bundle\MediaBundle\Duplicate\Type\FileDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Enhavo\Bundle\TranslationBundle\Duplicate\TranslationDuplicateTrait;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;

/**
 * Like the file type, but also duplicates the translated files of each locale.
 */
class TranslationFileDuplicateType extends AbstractDuplicateType
{
    use TranslationDuplicateTrait;

    public function __construct(TranslationManager $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $this->duplicateTranslations($sourceValue, $targetValue, function ($value) use ($options, $context) {
            return $this->duplicateWithParent($options, $value, $context);
        });
    }

    public static function getParentType(): ?string
    {
        return FileDuplicateType::class;
    }

    public static function getName(): ?string
    {
        return 'translation_file';
    }
}
