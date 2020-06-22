<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 14:31
 */

namespace Enhavo\Bundle\RoutingBundle\Metadata\Provider;

use Enhavo\Bundle\RoutingBundle\Metadata\Generator;
use Enhavo\Bundle\RoutingBundle\Metadata\Metadata;
use Enhavo\Component\Metadata\Exception\ProviderException;
use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;

/**
 * GeneratorParser.php
 *
 * @since 18/08/18
 * @author gseidel
 */
class GeneratorProvider implements ProviderInterface
{
    public function provide(BaseMetadata $metadata, $normalizedData)
    {
        if(!$metadata instanceof Metadata) {
            throw ProviderException::invalidType($metadata, Metadata::class);
        }

        if(array_key_exists('generators', $normalizedData) && is_array($normalizedData['generators'])) {
            foreach($normalizedData['generators'] as $config) {
                $generator = new Generator();
                $generator->setType($config['type']);
                unset($config['type']);
                $generator->setOptions($config);
                $metadata->addGenerator($generator);
            }
        }
    }
}
