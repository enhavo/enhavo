<?php

namespace Enhavo\Bundle\RoutingBundle\Util;

use Enhavo\Bundle\RoutingBundle\Repository\RouteRepository;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniquePrefixGenerator
{
    public function __construct(
        private RouteRepository $routeRepository,
    )
    {
    }

    public function generate(array $parts, array $options = []): string
    {
        $options = $this->resolveOptions($options);

        if ($options['slugify']) {
            foreach ($parts as $key => $part) {
                $parts[$key] = Slugifier::slugify($part);
            }
        }

        $counter = 0;
        do {
            $prefix = $this->build($parts, $options, $counter);
            ++$counter;
        } while ($this->exists($prefix, $options));

        return $prefix;
    }

    private function exists(string $prefix, array $options): bool
    {
        $criteria = ['staticPrefix' => $prefix];
        if (is_callable($options['exists'])) {
            return ($options['exists'])($this->routeRepository, $prefix);
        }

        return count($this->routeRepository->findBy($criteria)) > 0;
    }

    private function build(array $properties, array $options, int $counter): string
    {
        if (0 === $counter) {
            return $this->cut($this->format($properties, $options), $options['max_length']);
        }

        $suffix = sprintf('-%d', $counter);

        $base = $this->cut($this->format($properties, $options), $options['max_length'] - strlen($suffix));

        return $base.$suffix;
    }

    private function format(array $properties, array $options): string
    {
        if (null !== $options['format']) {
            $prefix = $options['format'];
            foreach ($properties as $key => $value) {
                $prefix = str_replace(sprintf('{%s}', $key), $value, $prefix);
            }

            return $prefix;
        }

        return sprintf('/%s', implode($options['separator'], $properties));
    }

    private function cut(string $prefix, int $maxLength): string
    {
        return substr($prefix, 0, max(0, $maxLength));
    }

    private function resolveOptions(array $options): array
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'format' => null,
            'max_length' => 255,
            'exists' => null,
            'unique_key' => null,
            'separator' => '/',
            'slugify' => true,
        ]);
        $resolver->setAllowedTypes('exists', ['null', 'callable']);
    }
}
