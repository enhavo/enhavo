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
 * AutoGeneratorParserParser.php
 *
 * @since 18/08/18
 * @author gseidel
 */
class GeneratorParser implements ParserInterface
{
    public function parse(array &$metadataArray, $configuration)
    {
        if(isset($configuration['generators']) && is_array($configuration['generators'])) {
            $data = $configuration['generators'];
            foreach($data as $name => $value) {
                $metadataArray['generators'][$name] = $value;
            }
        }
    }
}