<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\AppBundle\Area\AreaResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class AreaEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly AreaResolverInterface $areaResolver,
        private readonly Environment $twig,
        private readonly array $areas,
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'template' => null,
        ]);

        $area = $this->areaResolver->resolve();
        if ($area === null) {
            throw new \Exception('Can\'t resolve area');
        }

        $resolver->setNormalizer('template', function ($options, $value) use ($area) {
            return $this->twig->createTemplate($value)->render(['area' => $area]);
        });

        if (isset($this->areas[$area])) {
            $options = $this->areas[$area]['options'];
            $resolver->setDefaults($options);
        }
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
