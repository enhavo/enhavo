<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 14:31
 */

namespace Enhavo\Bundle\RoutingBundle\Metadata;

use Enhavo\Bundle\AppBundle\Metadata\ParserInterface;

/**
 * RouterParser.php
 *
 * @since 19/08/18
 * @author gseidel
 */
class RouterParser implements ParserInterface
{
    public function parse(array &$metadataArray, $configuration)
    {
        if(isset($configuration['router']) && is_array($configuration['router'])) {
            $data = $configuration['router'];
            foreach($data as $name => $value) {
                $metadataArray['router'][$name] = $value;
            }
        }
    }
}