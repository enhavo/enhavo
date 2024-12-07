<?php

/**
 * CancelButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class SaveActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly RouteResolverInterface $routeResolver,
    )
    {
    }

    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        if ($options['route']) {
            $url = $this->getUrl($options['route'], $options['route_parameters'], $resource);
        } else {
            if ($resource === null || $resource->getId() === null) {
                $route = $this->routeResolver->getRoute('create', ['api' => true]);
            } else {
                $route = $this->routeResolver->getRoute('update', ['api' => true]);
            }

            if ($route === null) {
                throw new \Exception(sprintf('Can\'t resolve route for resource "%s". You have to explicit define the route.', get_class($resource)));
            }

            $url = $this->getUrl($route, $options['route_parameters'], $resource);
        }

        $data->set('url', $url);
    }

    private function getUrl(string $route, array $routeParameters = [], object $resource = null): string
    {
        $parameters = [];
        if ($resource !== null && $resource->getId() !== null) {
            $parameters['id'] = $resource->getId();
        }
        $parameters = array_merge_recursive($parameters, $routeParameters);
        return $this->router->generate($route, $parameters);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'action.label.save',
            'translation_domain' => 'EnhavoResourceBundle',
            'icon' => 'save',
            'route' => null,
            'route_parameters' => [],
            'model' => 'SaveAction',
            'permission' => 'expr:permission(resource, "create")'
        ]);
    }

    public static function getName(): ?string
    {
        return 'save';
    }
}
