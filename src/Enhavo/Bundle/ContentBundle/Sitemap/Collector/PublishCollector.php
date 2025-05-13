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

use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapInterface;

/**
 * PublishCollector.php
 *
 * @since 05/07/16
 *
 * @author gseidel
 */
class PublishCollector extends RepositoryCollector
{
    public function getType()
    {
        return 'publish';
    }

    public function getUrls()
    {
        $resources = $this->getResources();
        $urls = [];
        foreach ($resources as $resource) {
            if ($resource instanceof Publishable && $resource->isPublished()) {
                if ($resource instanceof SitemapInterface && $resource->isNoIndex()) {
                    continue;
                }
                $urls[] = $this->convertToUrl($resource);
            }
        }

        return $urls;
    }
}
