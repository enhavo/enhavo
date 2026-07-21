<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Normalizer;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\TranslationBundle\EventListener\AccessControl;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Component\Metadata\MetadataRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TranslationNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        private AccessControl $accessControl,
        private MetadataRepository $metadataRepository,
        private LocaleResolverInterface $localeResolver,
        private TranslationManager $translationManager,
        private bool $enabled,
    ) {
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        $context[self::class] = true;

        $data = $this->normalizer->normalize($object, $format, $context);

        $locale = $this->localeResolver->resolve();

        $metadata = $this->metadataRepository->getMetadata($object);
        foreach ($metadata->getProperties() ?? [] as $key => $property) {
            if (array_key_exists($key, $data)) {
                $this->translationManager->getTranslations($object, $key);
                $type = $this->translationManager->getTranslation($object, $key);
                $value = $type->getTranslation($object, $key, $locale);
                if (is_string($value)) {
                    $data[$key] = $value;
                } elseif ($value instanceof FileInterface) {
                    $data[$key] = $this->normalizer->normalize($value, $format, $context);
                } elseif ($value instanceof Route) {
                    $data[$key] = $this->normalizer->normalize($value, $format, $context);
                }
            }
        }

        return $data;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (!$this->enabled) {
            return false;
        }

        if ($context[self::class] ?? false) {
            return false;
        }

        if (!$this->accessControl->isAccess()) {
            return false;
        }

        return is_object($data) && $this->metadataRepository->hasMetadata($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            '*' => false,
        ];
    }
}
