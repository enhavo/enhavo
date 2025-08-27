<?php

namespace Enhavo\Bundle\ContentBundle\StructuredData;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface StructuredDataTransformerInterface
{
    public function transform(mixed $value, array $options);

    public function configureOptions(OptionsResolver $resolver): void;

    public static function getName(): ?string;
}
