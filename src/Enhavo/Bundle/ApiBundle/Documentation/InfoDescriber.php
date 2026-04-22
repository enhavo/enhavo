<?php

namespace Enhavo\Bundle\ApiBundle\Documentation;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;
use Symfony\Component\OptionsResolver\OptionsResolver;


class InfoDescriber implements DescriberInterface
{
    public function describe(Documentation $documentation, array $options = []): void
    {
        $options = $this->getOptions($options);

        $info = $documentation->info();

        if ($options['title']) {
            $info->title($options['title']);
        }

        if ($options['description']) {
            $info->description($options['description']);
        }

        if ($options['version']) {
            $info->version($options['version']);
        }
    }

    private function getOptions(array $options): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'title' => null,
            'description' => null,
            'version' => null,
        ]);
        return $resolver->resolve($options);
    }
}
