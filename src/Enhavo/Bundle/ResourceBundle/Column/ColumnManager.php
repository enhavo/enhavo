<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-27
 * Time: 12:47
 */

namespace Enhavo\Bundle\ResourceBundle\Column;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ColumnManager
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly AuthorizationCheckerInterface $checker,
    )
    {
    }

    public function createColumnsViewData(array $configuration)
    {
        $data = [];
        foreach($configuration as $name => $options) {
            $column = $this->createColumn($options);

            if($column->getPermission() !== null && !$this->checker->isGranted($column->getPermission())) {
                continue;
            }

            $columnData = $column->createColumnViewData();
            $columnData['key'] = $name;
            $data[] = $columnData;
        }

        return $data;
    }

    public function createResourcesViewData(array $configuration, $resources, $childrenProperty = null, $positionProperty = null)
    {
        $columns = [];
        foreach($configuration as $name => $options) {
            $columns[$name] = $this->createColumn($options);
        }

        return $this->fetch($columns, $resources, $childrenProperty, $positionProperty);
    }

    public function getColumns(array $configuration): array
    {
        return [];
    }


    public function fetch(array $columns, $resources, $childrenProperty, $positionProperty)
    {
        $data = [];
        foreach($resources as $resource) {
            $columnData = [
                'id' => $resource->getId(),
            ];
            foreach($columns as $name => $column) {
                $columnData['data'][$name] = $column->createResourceViewData($resource);
                if($positionProperty) {
                    $columnData['position'] = $this->propertyAccessor->getValue($resource, $positionProperty);
                }
                if($childrenProperty) {
                    $children = $this->propertyAccessor->getValue($resource, $childrenProperty);
                    $columnData['children'] = $this->fetch($columns, $children, $childrenProperty, $positionProperty);
                }
            }
            $data[] = $columnData;
        }
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
        $column = new Column($type, $options);
        return $column;
    }
}
