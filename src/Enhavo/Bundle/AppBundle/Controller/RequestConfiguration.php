<?php
/**
 * ViewerFactory.php
 *
 * @since 28/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration as SyliusRequestConfiguration;

class RequestConfiguration extends SyliusRequestConfiguration implements RequestConfigurationInterface
{
    public function getViewerOptions()
    {
        $attributes = $this->parameters->get('viewer', []);
        if(isset($attributes['type'])) {
            unset($attributes['type']);
        }
        return $attributes;
    }

    public function getViewerType()
    {
        $attributes = $this->parameters->get('viewer', []);
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

        $templatesNamespace = $this->metadata->getTemplatesNamespace();

        if (false !== strpos($templatesNamespace, ':')) {
            return sprintf('%s:%s.%s', $templatesNamespace ?: ':', $name, 'twig');
        }

        return sprintf('%s/%s.%s', $templatesNamespace, $name, 'twig');
    }

    public function isAjaxRequest()
    {
        return $this->request->isXmlHttpRequest();
    }

    public function getSortingStrategy()
    {
        return $this->parameters->get('sorting_strategy', 'asc_first');
    }

    public function getBatchType()
    {
        return $this->request->get('type');
    }
}