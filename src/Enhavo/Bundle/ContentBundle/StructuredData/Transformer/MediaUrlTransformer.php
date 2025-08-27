<?php

namespace Enhavo\Bundle\ContentBundle\StructuredData\Transformer;

use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataTransformerInterface;
use Enhavo\Bundle\MediaBundle\Routing\UrlGeneratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaUrlTransformer implements StructuredDataTransformerInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function transform(mixed $value, array $options)
    {
        if ($value === null) {
            return null;
        }

        if ($options['format']) {
            return $this->urlGenerator->generateFormat($value, $options['format'], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $this->urlGenerator->generate($value, \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'format' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_url';
    }
}
