<?php

namespace Enhavo\Bundle\TranslationBundle\Client;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ContextNormalizer
{
    private array $textContextCache = [];
    private array $arrayContextCache = [];

    public function __construct(
        private NormalizerInterface $normalizer,
    )
    {
    }

    public function getText(mixed $context, array $groups = []): ?string
    {
        $contextData = null;
        if (is_string($context)) {
            return $context;
        } else if (is_object($context)) {
            $cacheKey = spl_object_hash($context) . join(',', $groups);
            if ($this->textContextCache[$cacheKey] ?? false) {
                $contextData = $this->textContextCache[$cacheKey];
            } else {
                $contextNormalized = $this->getArray($context, $groups);
                $contextData = "";
                array_walk_recursive($contextNormalized, function($value) use (&$contextData) {
                    if (is_string($value)) {
                        $contextData .= $value . ".";
                    }
                });
                $this->textContextCache[$cacheKey] = $contextData;
            }
        }
        return $contextData;
    }

    public function getArray(mixed $context, array $groups = []): array
    {
        $contextData = [];
        if (is_object($context)) {
            $cacheKey = spl_object_hash($context) . join(',', $groups);
            if ($this->arrayContextCache[$cacheKey] ?? false) {
                $contextData = $this->arrayContextCache[$cacheKey];
            } else {
                $contextData = $this->normalizer->normalize($context, null, ['groups' => $groups]);
                $this->arrayContextCache[$cacheKey] = $contextData;
            }
        }
        return $contextData;
    }
}
