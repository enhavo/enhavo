<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Sitemap\Collector;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ContentBundle\Model\SitemapUrl;
use Enhavo\Bundle\ContentBundle\Sitemap\CollectorInterface;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapInterface;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * PublishCollector.php
 *
 * @since 05/07/16
 *
 * @author gseidel
 */
class RepositoryCollector extends AbstractType implements CollectorInterface
{
    /**
     * @var array
     */
    protected $options;

    public function __construct(
        private readonly Router $router,
        private readonly ResourceManager $resourceManager,
    ) {
    }

    public function setOptions($options)
    {
        if (!isset($options['repository'])) {
            throw new \InvalidArgumentException(sprintf('repository not set for SitemapCollector type "%s"', $this->getType()));
        }

        $this->options = [
            'repository' => $options['repository'],
            'method' => $options['method'] ?? 'findAll',
        ];
    }

    protected function getResources()
    {
        if ($this->container->has($this->options['repository'])) {
            $repository = $this->container->get($this->options['repository']);
        } elseif (class_exists($this->options['repository'])) {
            $repository = $this->container->get('doctrine.orm.entity_manager')->getRepository($this->options['repository']);
        } elseif ($this->resourceManager->getMetadata($this->options['repository'])) {
            $repository = $this->resourceManager->getRepository($this->options['repository']);
        } else {
            throw new \Exception(sprintf('No repository found for value "%s"', $this->options['repository']));
        }
        $method = $this->options['method'];

        return call_user_func_array([$repository, $method], []);
    }

    protected function convertToUrl(SitemapInterface $resource)
    {
        $url = new SitemapUrl();
        $url->setLastModified($resource->getUpdatedAt());
        $url->setLocation($this->router->generate($resource, [], UrlGeneratorInterface::ABSOLUTE_URL));

        return $url;
    }

    public function getUrls()
    {
        $resources = $this->getResources();
        $urls = [];
        foreach ($resources as $resource) {
            if ($resource instanceof SitemapInterface && $resource->isNoIndex()) {
                continue;
            }
            $urls[] = $this->convertToUrl($resource);
        }

        return $urls;
    }

    public function getType()
    {
        return 'repository';
    }
}
