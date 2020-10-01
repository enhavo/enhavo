<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:30
 */

namespace Enhavo\Bundle\SearchBundle\Extractor\PropertyExtractor;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Extractor\Extractor;
use Enhavo\Bundle\SearchBundle\Extractor\PropertyExtractorInterface;

class ModelExtractor extends AbstractType implements PropertyExtractorInterface
{
    /**
     * @var Extractor
     */
    private $extractor;

    /**
     * ModelExtractor constructor.
     * @param Extractor $extractor
     */
    public function __construct(Extractor $extractor)
    {
        $this->extractor = $extractor;
    }

    public function extract($value, $options = [])
    {
        if ($value === null) {
            return [];
        }
        return $this->extractor->extract($value);
    }

    public function getType()
    {
        return 'model';
    }
}
