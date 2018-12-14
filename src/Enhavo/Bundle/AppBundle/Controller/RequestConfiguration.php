<?php
/**
 * ViewerFactory.php
 *
 * @since 28/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration as SyliusRequestConfiguration;

class RequestConfiguration extends SyliusRequestConfiguration
{
    public function getViewerOptions()
    {
        $attributes = $this->getParameters()->get('viewer', []);
        if(isset($attributes['type'])) {
            unset($attributes['type']);
        }
        return $attributes;
    }

    public function getViewerType()
    {
        $attributes = $this->getParameters()->get('viewer', []);
        if(isset($attributes['type'])) {
            return $attributes['type'];
        }
        return null;
    }

    public function getDefaultTemplate($name)
    {
        if(substr_count($name, ':') == 2) {
            return $name;
        }

        $templatesNamespace = $this->getMetadata()->getTemplatesNamespace();

        if (false !== strpos($templatesNamespace, ':')) {
            return sprintf('%s:%s.%s', $templatesNamespace ?: ':', $name, 'twig');
        }

        return sprintf('%s/%s.%s', $templatesNamespace, $name, 'twig');
    }

    public function isAjaxRequest()
    {
        return $this->getRequest()->isXmlHttpRequest();
    }

    public function getSortingStrategy()
    {
        return $this->getParameters()->get('sorting_strategy', 'asc_first');
    }

    public function getBatchType()
    {
        return $this->getRequest()->get('type');
    }

    public function getBatches()
    {
        return $this->getParameters()->get('batches', []);
    }

    public function getBatchOptions($type)
    {
        $batches = $this->getBatches();
        if(isset($batches[$type])) {
            return $batches[$type];
        }
        return null;
    }

    public function getFilters()
    {
        return $this->getFilters()->get('filters', []);
    }

    public function hasFilters()
    {
        return $this->getFilters()->has('filters');
    }
}