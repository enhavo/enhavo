<?php

namespace Enhavo\Bundle\ApiBundle\Documentation;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ServersDescriber implements DescriberInterface
{
    public function describe(Documentation $documentation, array $options = []): void
    {
        $options = $this->getOptions($options);

        foreach ($options['servers'] as $server) {
            $serverNode = $documentation->server($server['url']);
            if ($server['description']) {
                $serverNode->description($server['description']);
            }
        }
    }

    private function getOptions(array $options): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'servers' => [],
        ]);
        $resolver->setAllowedTypes('servers', 'array[]');
        $resolver->setNormalizer('servers', function ($options, $servers) {
            $normalized = [];
            foreach ($servers as $server) {
                $serverResolver = new OptionsResolver();
                $serverResolver->setRequired(['url']);
                $serverResolver->setDefaults([
                    'description' => null,
                ]);
                $serverResolver->setAllowedTypes('url', 'string');
                $serverResolver->setAllowedTypes('description', ['string', 'null']);
                $normalized[] = $serverResolver->resolve($server);
            }
            return $normalized;
        });
        return $resolver->resolve($options);
    }
}
