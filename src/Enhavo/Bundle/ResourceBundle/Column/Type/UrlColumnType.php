<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.11.17
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly Router $router,
    )
    {
    }

    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        $url = $this->router->generate($resource, [], UrlGeneratorInterface::ABSOLUTE_PATH, $options['resolver_type']);
        $data->set('url', $url);
        $data->set('target', $options['target']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-action',
            'model' => 'ActionColumn',
            'icon' => 'link',
            'target' => '_blank',
            'resolver_type' => 'default'
        ]);
    }

    public static function getName(): ?string
    {
        return 'url';
    }
}
