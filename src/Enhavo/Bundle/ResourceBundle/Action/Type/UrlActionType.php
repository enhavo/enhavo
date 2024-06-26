<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

abstract class UrlActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router
    )
    {
    }

    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $data->set('url', $this->getUrl($options, $resource));
    }

    private function getUrl(array $options, $resource = null): string
    {
        $parameters = [];

        if($options['append_id'] && $resource !== null && $resource->getId() !== null) {
            $parameters[$options['append_key']] = $resource->getId();
        }

        $parameters = array_merge_recursive($parameters, $options['route_parameters']);

        return $this->router->generate($options['route'], $parameters);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'route_parameters' => [],
            'append_id' => false,
            'append_key' => 'id'
        ]);

        $resolver->setRequired([
            'route',
        ]);
    }
}
