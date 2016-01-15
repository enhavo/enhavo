<?php
/**
 * OrderViewer.php
 *
 * @since 12/01/16
 * @author fliebl
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

class OrderViewer extends AbstractViewer
{
    public function getDefaultConfig()
    {
        return array(
            'sorting' => 'DESC'
        );
    }

    protected function getSorting()
    {
        $sorting = $this->getConfig()->get('sorting');
        $isAsc = $sorting && is_string($sorting) && (strtoupper($sorting) == 'ASC');
        return $isAsc ? 'ASC' : 'DESC';
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        $parameters = array(
            'viewer' => $this,
            'data' => $this->getResource(),
            'sorting' => $this->getSorting()
        );

        $parameters = array_merge($this->getTemplateVars(), $parameters);

        return $parameters;
    }
}
