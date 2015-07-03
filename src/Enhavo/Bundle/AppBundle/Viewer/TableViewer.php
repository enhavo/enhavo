<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;

class TableViewer extends AbstractViewer
{
    public function getDefaultConfig()
    {
        return array(
            'table' => array(
                'columns' => array(
                    'id' => array(
                        'label' => 'ID',
                        'property' => 'id'
                    )
                ),
            )
        );
    }

    protected function getColumns()
    {
        $columns = $this->getConfig()->get('table.columns');
        foreach($columns as &$column) {
            if(!array_key_exists('width', $column)) {
                $column['width'] = 1;
            }
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

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        $parameters = array(
            'viewer' => $this,
            'data' => $this->getResource(),
            'columns' => $this->getColumns()
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

    public function renderWidget($widget, $property, $item)
    {
        $templateEngine = $this->container->get('templating');
        return $templateEngine->render($widget, array(
            'data' => $item,
            'value' => $this->getProperty($item, $property)
        ));
    }
}
