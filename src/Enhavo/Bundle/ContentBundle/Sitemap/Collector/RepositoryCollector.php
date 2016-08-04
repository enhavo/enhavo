<?php

namespace Enhavo\Bundle\ContentBundle\Sitemap\Collector;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ContentBundle\Model\SitemapUrl;
use Enhavo\Bundle\ContentBundle\Sitemap\CollectorInterface;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapInterface;

/**
 * PublishCollector.php
 *
 * @since 05/07/16
 * @author gseidel
 */
class RepositoryCollector extends AbstractType implements CollectorInterface
{
    /**
     * @var array
     */
    protected $options;

    public function setOptions($options)
    {
        if(!isset($options['repository'])) {
            throw new \InvalidArgumentException(sprintf('repository not set for SitemapCollector type "%s"', $this->getType()));
        }

        $this->options = [
            'repository' => $options['repository'],
            'method' => isset($options['method']) ? $options['method']: 'findAll'
        ];
    }

    protected function getResources()
    {
        if(strpos(':', $this->options['repository']) === false) {
            $repository = $this->container->get($this->options['repository']);
        } else {
            $repository = $this->container->get('doctrine.orm.entity_manager')->getRepository($this->options['repository']);
        }
        $method = $this->options['method'];

        return call_user_func_array([$repository, $method], []);
    }

    protected function convertToUrl(SitemapInterface $resource)
    {
        $urlResolver = $this->container->get('enhavo_app.url_resolver');

        $url = new SitemapUrl();
        $url->setChangeFrequency($resource->getChangeFrequency());
        $url->setLastModified($resource->getUpdated());
        $url->setPriority($resource->getPriority());
        $url->setLocation($urlResolver->resolve($resource));

        return $url;
    }

    public function getUrls()
    {
        $resources = $this->getResources();
        $urls = [];
        foreach($resources as $resource) {
            $urls[] = $this->convertToUrl($resource);
        }
        return $urls;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'repository';
    }
}