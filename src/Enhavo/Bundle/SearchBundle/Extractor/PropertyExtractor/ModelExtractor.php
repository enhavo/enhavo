<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:30
 */

namespace Enhavo\Bundle\SearchBundle\Extractor\PropertyExtractor;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Extractor\PropertyExtractorInterface;

class ModelExtractor extends AbstractType implements PropertyExtractorInterface
{
    public function extract($value, $options = [])
    {
        return $this->container->get('enhavo_search.extractor.extractor')->extract($value);
    }

    public function getType()
    {
        return 'model';
    }
}