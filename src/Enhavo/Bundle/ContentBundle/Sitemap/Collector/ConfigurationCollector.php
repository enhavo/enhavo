<?php
/**
 * ConfigurationCollector.php
 *
 * @since 01/03/17
 * @author gseidel
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
        if(!isset($options['entries'])) {
            throw new \InvalidArgumentException(sprintf('entries not defined', $this->getType()));
        }

        $this->options = $options;
    }

    public function getUrls()
    {
        $entries = $this->options['entries'];
        $urls = [];
        foreach($entries as $entry) {
            $url = new SitemapUrl();
            if(isset($entry['lastmod'])) {
                $url->setLastModified(new \DateTime($entry['lastmod']));
            }
            if(isset($entry['loc'])) {
                $url->setLocation($entry['loc']);
            }
            $urls[] = $url;
        }
        return $urls;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'configuration';
    }
}
