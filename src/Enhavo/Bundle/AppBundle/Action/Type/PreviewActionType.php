<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author gseidel
 */
class PreviewActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly RouteResolverInterface $routeResolver,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    )
    {
    }

    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        $apiRoute = $this->expressionLanguage->evaluate($options['api_route'], [
            'resource' => $resource,
        ]);

        if ($apiRoute === null) {
            $apiRoute = $this->routeResolver->getRoute('preview', ['api' => true]);
        }

        if ($apiRoute === null) {
            throw new \Exception('Can\'t find an api route for preview, please provide a route over the "api_route" option');
        }

        $apiParameters = $this->expressionLanguage->evaluateArray($options['api_route_parameters'], [
            'resource' => $resource,
        ]);
        $data->set('apiUrl', $this->router->generate($apiRoute, $apiParameters));
        $data->set('selectors', is_array($options['selectors']) ? $options['selectors'] : [$options['selectors']]);
        $data->set('forceReload', $options['force_reload']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'label.preview_frame',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'remove_red_eye',
            'model' => 'PreviewAction',
            'component' => 'action-preview',
            'route' => 'enhavo_app_admin_resource_preview',
            'selectors' => ['main'],
            'force_reload' => false,
            'api_route' => null,
            'api_route_parameters' => [
                'id' => 'expr:resource?.getId()'
            ]
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
