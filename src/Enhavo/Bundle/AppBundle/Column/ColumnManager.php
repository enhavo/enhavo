<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-27
 * Time: 12:47
 */

namespace Enhavo\Bundle\AppBundle\Column;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;

class ColumnManager
{
    /**
     * @var TypeCollector
     */
    private $collector;

    /**
     * ActionManager constructor.
     * @param TypeCollector $collector
     */
    public function __construct(TypeCollector $collector)
    {
        $this->collector = $collector;
    }

    public function createColumnsViewData(array $configuration)
    {
        $data = [];
        foreach($configuration as $name => $options) {
            $column = $this->createColumn($options);
            $data[] = $column->createColumnViewData();
        }

        return $data;
    }

    public function createResourcesViewData(array $configuration)
    {
        $data = [];
        return $data;
    }

    /**
     * @param $options
     * @return Column
     * @throws TypeMissingException
     */
    private function createColumn($options)
    {
        if(!isset($options['type'])) {
            throw new TypeMissingException(sprintf('No type was given to create "%s"', Column::class));
        }

        /** @var ColumnTypeInterface $type */
        $type = $this->collector->getType($options['type']);
        unset($options['type']);
        $action = new Column($type, $options);
        return $action;
    }
}
