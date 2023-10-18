<?php

namespace Enhavo\Bundle\ApiBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Laminas\Stdlib\PriorityQueue;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DataNormalizer implements NormalizerInterface
{
    private readonly PriorityQueue $normalizers;
    private ?ContainerInterface $container = null;

    public function __construct(
        private readonly NormalizerInterface $defaultNormalizer,
    ) {
        $this->normalizers = new PriorityQueue();
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function register($class, $priority = 10): void
    {
        $this->normalizers->insert($class, $priority);
    }

    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $normalizedData = $this->defaultNormalizer->normalize($object, $format, $context);
        $data = new Data($normalizedData);

        foreach ($this->normalizers as $normalizer) {
            $types = call_user_func([$normalizer, 'getSupportedTypes']);
            foreach ($types as $type) {
                if (is_subclass_of($object, $type)) {
                    /** @var DataNormalizerInterface $service */
                    $service = $this->container->get($normalizer);
                    $service->buildData($data, $object, $format, $context);
                    break;
                }
            }
        }

        return $data->normalize();
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        foreach ($this->normalizers as $normalizer) {
            $types = call_user_func([$normalizer, 'getSupportedTypes']);
            foreach ($types as $type) {
                if (is_subclass_of($data, $type)) {
                    return true;
                }
            }
        }

        return false;
    }
}
