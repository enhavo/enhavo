<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author gseidel
 */
class TwigExtension extends AbstractExtension
{
    public function __construct(
        private NormalizerInterface $normalizer,
    )
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFunction('normalize', [$this, 'normalize']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('normalize', [$this, 'normalize']),
        ];
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        return $this->normalizer->normalize($object, $format, $context);
    }
}
