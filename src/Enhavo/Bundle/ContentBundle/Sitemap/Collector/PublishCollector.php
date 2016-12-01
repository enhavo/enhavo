<?php

namespace Enhavo\Bundle\ContentBundle\Sitemap\Collector;
use Enhavo\Bundle\ContentBundle\Content\Publishable;

/**
 * PublishCollector.php
 *
 * @since 05/07/16
 * @author gseidel
 */
class PublishCollector extends RepositoryCollector
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'publish';
    }

    public function setOptions($options)
    {
        if(!isset($options['repository'])) {
            throw new \InvalidArgumentException(sprintf('repository not set for SitemapCollector type "%s"', $this->getType()));
        }

        $this->options = [
            'repository' => $options['repository'],
            'method' => isset($options['method']) ? $options['method']: 'findPublished'
        ];
    }

    public function getUrls()
    {
        $resources = $this->getResources();
        $urls = [];
        foreach($resources as $resource) {
            if($resource instanceof Publishable && $resource->isPublic()) {
                $urls[] = $this->convertToUrl($resource);
            }
        }
        return $urls;
    }
}