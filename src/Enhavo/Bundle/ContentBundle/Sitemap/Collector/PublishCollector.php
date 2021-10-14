<?php

namespace Enhavo\Bundle\ContentBundle\Sitemap\Collector;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapInterface;
use Pagerfanta\Pagerfanta;

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

    public function getUrls()
    {
        $resources = $this->getResources();
        $urls = [];
        foreach($resources as $resource) {
            if($resource instanceof Publishable && $resource->isPublished()) {
                if ($resource instanceof SitemapInterface && $resource->isNoIndex()) {
                    continue;
                }
                $urls[] = $this->convertToUrl($resource);
            }
        }
        return $urls;
    }
}
