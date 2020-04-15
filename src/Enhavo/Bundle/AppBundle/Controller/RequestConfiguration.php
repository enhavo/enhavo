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
        return $name;
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

    public function getFilters()
    {
        return $this->getParameters()->get('filters', []);
    }

    public function hasFilters()
    {
        return $this->getParameters()->has('filters');
    }

    public function getSorting(array $sorting = [])
    {
        $data = json_decode($this->getRequest()->getContent(), true);
        if(is_array($data) && isset($data['sorting']) && count($data['sorting']) > 0) {
            foreach($data['sorting'] as $sort) {
                $sorting[$sort['property']] = $sort['direction'];
            }
            return $sorting;
        }

        return parent::getSorting($sorting);
    }

    public function isPaginated()
    {
        if ($this->getRequest()->query->has('paginate')) {
            return $this->getRequest()->get('paginate');
        }

        return parent::isPaginated();
    }

    public function getHydrate()
    {
        return $this->getRequest()->get('hydrate', 'object');
    }
}
