<?php

namespace Enhavo\Bundle\ApiBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Laminas\Stdlib\PriorityQueue;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DataNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private readonly PriorityQueue $normalizers;
    private ?ContainerInterface $container = null;

    public function __construct() {
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
        if (!isset($context[DataNormalizer::class])) {
            $context[DataNormalizer::class] = [];
        }
        $context[DataNormalizer::class][spl_object_hash($object)] = true;


        $normalizers = $this->getNormalizerToExecute($object, $context);
        $normalizedData = $this->getNormalizedData($normalizers, $object, $format, $context);

        $data = new Data($normalizedData);
        foreach ($normalizers as $normalizer) {
            $normalizer->buildData($data, $object, $format, $context);
            if ($normalizer->isStopped()) {
                break;
            }
        }

        return $data->normalize();
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        if (!is_object($data)) {
            return false;
        }

        if (isset($context[DataNormalizer::class][spl_object_hash($data)])) {
            return false;
        }

        foreach ($this->normalizers as $normalizer) {
            $types = call_user_func([$normalizer, 'getSupportedTypes']);
            foreach ($types as $type) {
                if (is_a($data, $type) || is_subclass_of($data, $type)) {
                    return true;
                }
            }
        }

        return false;
    }

    /** @param $normalizers DataNormalizerInterface[] */
    private function getNormalizedData(array $normalizers, $object, $format, $context): array
    {
        $groups = [];
        if (isset($context['groups'])) {
            if (is_array($context['groups'])) {
                $groups = $context['groups'];
            } elseif (is_string($context['groups'])) {
                $groups = [$context['groups']];
            }
        }
        foreach ($normalizers as $normalizer) {
            $groups = $normalizer->getSerializationGroups($groups, $context);
        }

        if ($groups === null) {
            return [];
        }

        $context['groups'] = $groups;
        return $this->normalizer->normalize($object, $format, $context);
    }

    /** @return DataNormalizerInterface[] */
    private function getNormalizerToExecute($object, $context): array
    {
        $normalizerToExecute = [];
        foreach ($this->normalizers as $normalizer) {
            if (isset($context['prevent_data_normalizers'][$normalizer])) {
                continue;
            }

            $types = call_user_func([$normalizer, 'getSupportedTypes']);
            foreach ($types as $type) {
                if (is_a($object, $type) || is_subclass_of($object, $type)) {
                    /** @var DataNormalizerInterface $service */
                    $service = $this->container->get($normalizer);
                    if ($service instanceof NormalizerAwareInterface) {
                        $service->setNormalizer($this->normalizer);
                    }
                    $normalizerToExecute[] = $service;
                    break;
                }
            }
        }
        return $normalizerToExecute;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            '*' => false,
        ];
    }
}
