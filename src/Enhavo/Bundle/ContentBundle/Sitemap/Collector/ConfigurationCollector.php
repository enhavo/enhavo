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

class ConfigurationCollector extends AbstractType implements CollectorInterface
{
    /**
     * @var array
     */
    protected $options;

    public function setOptions($options)
    {
        if (!isset($options['entries'])) {
            throw new \InvalidArgumentException(sprintf('entries not defined', $this->getType()));
        }

        $this->options = $options;
    }

    public function getUrls()
    {
        $entries = $this->options['entries'];
        $urls = [];
        foreach ($entries as $entry) {
            $url = new SitemapUrl();
            if (isset($entry['lastmod'])) {
                $url->setLastModified(new \DateTime($entry['lastmod']));
            }
            if (isset($entry['loc'])) {
                $url->setLocation($entry['loc']);
            }
            $urls[] = $url;
        }

        return $urls;
    }

    public function getType()
    {
        return 'configuration';
    }
}
