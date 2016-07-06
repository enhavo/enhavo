<?php
/**
 * SitemapGenerator.php
 *
 * @since 05/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Sitemap;

use Enhavo\Bundle\ContentBundle\Model\SitemapImage;
use Enhavo\Bundle\ContentBundle\Model\SitemapUrl;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\ContentBundle\Model\SitemapVideo;
use XMLWriter;

class SitemapGenerator
{
    /**
     * @var TypeCollector
     */
    private $collector;

    /**
     * @var array
     */
    private $configuration;

    public function __construct($configuration, TypeCollector $collector)
    {
        $this->configuration = $configuration;
        $this->collector = $collector;
    }

    protected function getUrls()
    {
        /** @var SitemapUrl[] $urls */
        $urls = [];
        foreach($this->configuration as $name => $configuration) {
            if(!isset($configuration['type'])) {
                throw new \InvalidArgumentException(sprintf('SitemapCollector "%s" has not type', $name));
            }
            $type = $configuration['type'];
            /** @var CollectorInterface $collector */
            $collector = $this->collector->getType($type);
            $collector = clone $collector;
            $collector->setOptions($configuration);
            $urls = array_merge($urls, $collector->getUrls());
        }
        return $urls;
    }

    public function generate()
    {
        $urls = $this->getUrls();

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('urlset');

        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->writeAttribute('xmlns:video', 'http://www.google.com/schemas/sitemap-video/1.1');
        $xml->writeAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');


        /** @var SitemapUrl $url */
        foreach($urls as $url) {
            $xml->startElement('url');

            $xml->writeElement('loc', $url->getLocation());
            $xml->writeElement('lastmod', $url->getLastModified()->format('c'));
            $xml->writeElement('changefreq', $url->getChangeFrequency());
            $xml->writeElement('priority', $url->getPriority());

            /** @var SitemapImage $image */
            foreach($url->getImages() as $image) {
                $xml->startElement('image:image');
                $xml->writeElement('image:loc', $image->getLocation());
                $xml->endElement();
            }

            /** @var SitemapVideo $video */
            foreach($url->getVideos() as $video) {
                $xml->startElement('video:video');
                $xml->writeElement('video:thumbnail_loc', $video->getThumbnailLocation());
                $xml->writeElement('video:title', $video->getTitle());
                $xml->writeElement('video:description', $video->getDescription());
                $xml->writeElement('video:content_loc', $video->getContentLocation());
                $xml->endElement();
            }

            $xml->endElement();
        }

        $xml->endElement();
        $xml->endDocument();

        return $xml->outputMemory();
    }

    /*
    public function createSitemapIndex($loc, $lastmod = 'Today') {
        $this->endSitemap();
        $indexwriter->openURI($this->getPath() . $this->getFilename() . self::SEPERATOR . self::INDEX_SUFFIX . self::EXT);
        $indexwriter->startDocument('1.0', 'UTF-8');
        $indexwriter->setIndent(true);
        $indexwriter->startElement('sitemapindex');
        $indexwriter->writeAttribute('xmlns', self::SCHEMA);
        for ($index = 0; $index < $this->getCurrentSitemap(); $index++) {
            $indexwriter->startElement('sitemap');
            $indexwriter->writeElement('loc', $loc . $this->getFilename() . ($index ? self::SEPERATOR . $index : '') . self::EXT);
            $indexwriter->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
            $indexwriter->endElement();
        }
        $indexwriter->endElement();
        $indexwriter->endDocument();
    }

    public function addItem($loc, $priority = self::DEFAULT_PRIORITY, $changefreq = NULL, $lastmod = NULL) {
        if (($this->getCurrentItem() % self::ITEM_PER_SITEMAP) == 0) {
            if ($this->getWriter() instanceof XMLWriter) {
                $this->endSitemap();
            }
            $this->startSitemap();
            $this->incCurrentSitemap();
        }
        $this->incCurrentItem();
        $this->getWriter()->startElement('url');
        $this->getWriter()->writeElement('loc', $this->getDomain() . $loc);
        $this->getWriter()->writeElement('priority', $priority);
        if ($changefreq)
            $this->getWriter()->writeElement('changefreq', $changefreq);
        if ($lastmod)
            $this->getWriter()->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
        $this->getWriter()->endElement();
        return $this;
    }
    */

}