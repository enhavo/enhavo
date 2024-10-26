<?php
/**
 * DeleteButton.php
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
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class DeleteActionType extends AbstractActionType
{
    public function __construct(
        private readonly CsrfTokenManager $tokenManager,
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
            $route = $this->routeResolver->getRoute('delete', ['api' => true]);

            if ($route === null) {
                throw new \Exception(sprintf('Can\'t resolve route for resource "%s". You have to explicit define the route.', get_class($resource)));
            }

            $url = $this->getUrl($route, $options['route_parameters'], $resource);
        }

        $data->set('url', $url);
        $data->set('token', $this->tokenManager->getToken('resource_delete')->getValue());
    }

    private function getUrl(string $route, array $routeParameters = [], object $resource = null): string
    {
        $parameters = [];
        $parameters['id'] = $resource->getId();
        $parameters = array_merge_recursive($parameters, $routeParameters);
        return $this->router->generate($route, $parameters);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'action.label.delete',
            'translation_domain' => 'EnhavoResourceBundle',
            'icon' => 'delete',
            'confirm' => true,
            'confirm_message' => 'delete.message.confirm',
            'confirm_label_ok' => 'delete.action.ok',
            'confirm_label_cancel' => 'delete.action.cancel',
            'append_id' => true,
            'model' => 'DeleteAction',
            'route' => null,
            'route_parameters' => [],
        ]);
    }

    public static function getName(): ?string
    {
        return 'delete';
    }
}
