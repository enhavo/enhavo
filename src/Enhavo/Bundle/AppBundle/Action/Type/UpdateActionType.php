<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouteResolverInterface $routeResolver,
    )
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'edit',
            'label' => 'label.edit',
            'translation_domain' => 'EnhavoAppBundle',
            'frame_key' => 'edit-view',
            'target' => '_frame',
            'route_parameters' => [
                'id' => 'expr:resource?.getId()'
            ]
        ]);


        $resolver->setNormalizer('route', function($options, $value) {
            if (empty($value)) {
                $route = $this->routeResolver->getRoute('update', ['api' => false]);
                if ($route) {
                    return $route;
                }
            }

            return $value;
        });

        $resolver->setRequired(['route']);
    }

    public static function getParentType(): ?string
    {
        return OpenActionType::class;
    }

    public static function getName(): ?string
    {
        return 'update';
    }
}
