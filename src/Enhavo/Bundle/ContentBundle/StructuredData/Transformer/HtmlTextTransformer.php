<?php

namespace Enhavo\Bundle\ContentBundle\StructuredData\Transformer;

use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataTransformerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlTextTransformer implements StructuredDataTransformerInterface
{
    public function transform(mixed $value, array $options)
    {
        if ($value === null) {
            return null;
        }

        return strip_tags($value);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

    }

    public static function getName(): ?string
    {
        return 'html_text';
    }
}
