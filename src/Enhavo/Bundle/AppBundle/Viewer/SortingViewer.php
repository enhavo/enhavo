<?php
/**
 * SortingViewer.php
 *
 * @since 12/01/16
 * @author fliebl
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class SortingViewer extends AbstractViewer
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
        if (!$sorting) {
            return 'DESC';
        }
        if (strtoupper($sorting) == 'ASC') {
            return 'ASC';
        } elseif (strtoupper($sorting) == 'DESC') {
            return 'DESC';
        } else {
            throw new InvalidConfigurationException('Invalid configuration value for _viewer.sorting, expecting "ASC" or "DESC", got "' . $sorting . '"');
        }
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
