<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 14:31
 */

namespace Enhavo\Bundle\SearchBundle\Metadata\Provider;

use Enhavo\Bundle\SearchBundle\Metadata\Filter;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Component\Metadata\Exception\ProviderException;
use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;

class FilterProvider implements ProviderInterface
{
    public function provide(BaseMetadata $metadata, $normalizedData)
    {
        if(!$metadata instanceof Metadata) {
            throw ProviderException::invalidType($metadata, Metadata::class);
        }

        if(array_key_exists('filter', $normalizedData) && is_array($normalizedData['filter'])) {
            foreach($normalizedData['filter'] as $key => $options) {
                $filter = new Filter();
                $filter->setKey($key);

                $filter->setType($options['type']);
                unset($options['type']);

                if(isset($options['data_type'])) {
                    $filter->setDataType($options['data_type']);
                    unset($options['data_type']);
                }

                $filter->setOptions($options);
                $metadata->addFilter($filter);
            }
        }
    }
}
