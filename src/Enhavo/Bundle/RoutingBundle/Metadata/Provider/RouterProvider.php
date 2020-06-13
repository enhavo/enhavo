<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 14:31
 */

namespace Enhavo\Bundle\RoutingBundle\Metadata\Provider;

use Enhavo\Bundle\RoutingBundle\Metadata\Metadata;
use Enhavo\Bundle\RoutingBundle\Metadata\Router;
use Enhavo\Component\Metadata\Exception\ProviderException;
use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;


/**
 * RouterParser.php
 *
 * @since 19/08/18
 * @author gseidel
 */
class RouterProvider implements ProviderInterface
{
    public function provide(BaseMetadata $metadata, $normalizedData)
    {
        if(!$metadata instanceof Metadata) {
            throw ProviderException::invalidType($metadata, Metadata::class);
        }

        if(array_key_exists('router', $normalizedData)) {
            foreach($normalizedData['router'] as $name => $config) {
                $router = new Router();
                $router->setType($config['type']);
                $router->setName($name);
                unset($config['type']);
                $router->setOptions($config);
                $metadata->addRouter($router);
            }
        }
    }
}
