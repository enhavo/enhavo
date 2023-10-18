<?php

namespace Enhavo\Bundle\ApiBundle\Normalizer;

use Laminas\Stdlib\PriorityQueue;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ChainNormalizer implements NormalizerInterface
{
    /** @var PriorityQueue<NormalizerInterface> */
    private readonly PriorityQueue $normalizers;

    public function __construct()
    {
        $this->normalizers = new PriorityQueue();
    }

    public function addNormalizer(NormalizerInterface $normalizer, int $priority = 10)
    {
        $this->normalizers->insert($normalizer, $priority);
    }

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supportsNormalization($object, $format)) {
                return $normalizer->normalize($object, $format, $context);
            }
        }
        return null;
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supportsNormalization($data, $format)) {
                return true;
            }
        }

        return false;
    }
}
