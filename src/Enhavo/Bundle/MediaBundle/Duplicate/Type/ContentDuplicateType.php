<?php

namespace Enhavo\Bundle\MediaBundle\Duplicate\Type;

use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentDuplicateType extends AbstractDuplicateType
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $content = $sourceValue->getValue();
        if ($content instanceof ContentInterface) {
            if ($options['copy_content']) {
                $targetValue->setValue(new Content($content->getContent()));
            } else {
                $targetValue->setValue(new PathContent($content->getFilePath()));
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'copy_content' => null,
        ]);
    }
}
