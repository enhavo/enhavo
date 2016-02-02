<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Enhavo\Bundle\AppBundle\Exception\TableWidgetException;

class TableViewer extends AbstractViewer
{
    public function getDefaultConfig()
    {
        return array(
            'table' => array(
                'sorting' => array(
                    'sortable' => false,
                    'move_up_route' => sprintf('%s_%s_move_up', $this->getBundlePrefix(), $this->getResourceName()),
                    'move_down_route' => sprintf('%s_%s_move_down', $this->getBundlePrefix(), $this->getResourceName())
                )
            )
        );
    }

    protected function getColumns()
    {
        $columns = $this->getConfig()->get('table.columns');
        if (!$columns) {
            if ($this->isSortable()) {
                $columns = array(
                    'id' => array(
                        'label' => 'id',
                        'property' => 'id',
                        'width' => 1
                    ),
                    'position' => array(
                        'label' => '',
                        'property' => 'position',
                        'width' => 1,
                        'widget' => 'EnhavoAppBundle:Widget:position.html.twig'
                    )
                );
            } else {
                $columns = array(
                    'id' => array(
                        'label' => 'id',
                        'property' => 'id',
                        'width' => 1
                    )
                );
            }

        }
        foreach($columns as $key => &$column) {
            if(!array_key_exists('width', $column)) {
                $column['width'] = 1;
            }
        }
        if (isset($columns['position']) && !isset($columns['position']['widget'])) {
            $columns['position']['widget'] = 'EnhavoAppBundle:Widget:position.html.twig';
        }

        return $columns;
    }

    protected function getConfigTableWidth()
    {
        $width = $this->getConfig()->get('table.width');
        if($width === null) {
            return 12;
        }
        return $width;
    }

    protected function getSorting()
    {
        $sorting = $this->getConfig()->get('table.sorting');

        if (!$sorting) {
            $sorting = array();
        }

        if (!isset($sorting['sortable'])) {
            $sorting['sortable'] = false;
        }
        if (!isset($sorting['move_up_route'])) {
            $sorting['move_up_route'] = sprintf('%s_%s_move_up', $this->getBundlePrefix(), $this->getResourceName());
        }
        if (!isset($sorting['move_down_route'])) {
            $sorting['move_down_route'] = sprintf('%s_%s_move_down', $this->getBundlePrefix(), $this->getResourceName());
        }

        return $sorting;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        $parameters = array(
            'viewer' => $this,
            'data' => $this->getResource(),
            'columns' => $this->getColumns(),
            'translationDomain' => $this->getTranslationDomain()
        );

        $parameters = array_merge($this->getTemplateVars(), $parameters);

        return $parameters;
    }

    /**
     * Return the value the given property and object.
     *
     * @param $resource
     * @param $property
     * @return mixed
     * @throws PropertyNotExistsException
     */
    public function getProperty($resource, $property)
    {
        $method = sprintf('get%s', ucfirst($property));
        if(method_exists($resource, $method)) {
            return call_user_func(array($resource, $method));
        }
        throw new PropertyNotExistsException(sprintf(
            'Trying to call "%s" on class "%s", but method does not exists. Maybe you spell it wrong you did\'t add the getter for property "%s"',
            $method,
            get_class($resource),
            $property
        ));
    }

    public function getTableWidth()
    {
        return $this->getConfigTableWidth();
    }

    public function isSortable()
    {
        $sorting = $this->getSorting();
        return $sorting['sortable'] === true;
    }

    public function getMoveUpRoute()
    {
        $sorting = $this->getSorting();
        return $sorting['move_up_route'];
    }

    public function getMoveDownRoute()
    {
        $sorting = $this->getSorting();
        return $sorting['move_down_route'];
    }

    public function renderWidget($options, $property, $item)
    {
        $collector = $this->container->get('enhavo_app.table_widget_collector');
        $widgets = array();
        foreach($collector->getCollection() as $widget) {
            $widgets[] = $widget->getType();
            if($widget->getType() == $options['type']) {
                return $widget->render($options, $property, $item);
            }
        }
        throw new TableWidgetException(sprintf(
            'TableWidget type "%s" not found. Did you mean one of them "%s".',
            $options['type'],
            implode(', ', $widgets)
        ));
    }
}
