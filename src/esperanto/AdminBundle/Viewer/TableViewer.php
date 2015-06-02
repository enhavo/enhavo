<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Viewer;


class TableViewer extends AbstractViewer
{
    public function getColumns()
    {
        return $this->getConfig()->get('table.columns');
    }

    public function getParameters()
    {
        $parameters = array(
            'viewer' => $this,
            'data' => $this->getResource(),
            'columns' => $this->getColumns()
        );

        return $parameters;
    }

    public function getProperty($resource, $property)
    {
        $method = sprintf('get%s', ucfirst($property));
        $value = call_user_func(array($resource, $method));
        return $value;
    }
}