<?php

namespace Enhavo\Bundle\ContentBundle\Sitemap\Collector;

use Enhavo\Bundle\RoutingBundle\Router\Router;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ContentBundle\Model\SitemapUrl;
use Enhavo\Bundle\ContentBundle\Sitemap\CollectorInterface;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    /**
     * @var Router
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

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
        $url = new SitemapUrl();
        $url->setLastModified($resource->getUpdated());
        $url->setLocation($this->router->generate($resource, [], UrlGeneratorInterface::ABSOLUTE_URL));

        return $url;
    }

    public function getUrls()
    {
        $resources = $this->getResources();
        $urls = [];
        foreach($resources as $resource) {
            if ($resource instanceof SitemapInterface && $resource->isNoIndex()) {
                continue;
            }
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
