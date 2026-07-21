<?php

namespace Enhavo\Bundle\ContentBundle\StructuredData\Transformer;

use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataTransformerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTransformer implements StructuredDataTransformerInterface
{
    public function transform(mixed $value, array $options)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }

        throw new \Exception(sprintf('Structured Data Transformation Error: Transformer type %s only works on property type %s', self::class, \DateTime::class));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    public static function getName(): ?string
    {
        return 'date';
    }
}
