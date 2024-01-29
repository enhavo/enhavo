<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class AreaEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly Environment $twig,
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('area');

        $resolver->setDefaults([
            'template' => null,
        ]);

        $resolver->setNormalizer('template', function($options, $value) {
            return $this->twig->createTemplate($value)->render(['area' => $options['area']]);
        });
    }

    public static function getParentType(): ?string
    {
        return ViewEndpointType::class;
    }

    public static function getName(): ?string
    {
        return 'area';
    }
}
