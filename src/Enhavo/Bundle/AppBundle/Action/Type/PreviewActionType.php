<?php

/**
 * PreviewButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class PreviewActionType extends AbstractActionType
{
    public function __construct(
        private RouterInterface $router,
        private RouteResolverInterface $routeResolver,
    )
    {
    }

    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $apiRoute = $options['api_route'] ?? $this->routeResolver->getRoute('preview', ['api' => true]);

        if ($apiRoute === null) {
            throw new \Exception('Can\'t find an api route for preview, please provide a route over the "api_route" option');
        }

        $data->set('apiUrl', $this->router->generate($apiRoute, [
            'id' => $resource->getId()
        ]));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'label.preview',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'remove_red_eye',
            'append_id' => true,
            'model' => 'PreviewAction',
            'component' => 'action-preview',
            'route' => 'enhavo_app_admin_resource_preview',
            'api_route' => null
        ]);
    }

    public static function getParentType(): ?string
    {
        return UrlActionType::class;
    }

    public static function getName(): ?string
    {
        return 'preview';
    }
}
