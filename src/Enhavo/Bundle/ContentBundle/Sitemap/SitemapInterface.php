<?php
/**
 * SitemapInterface.php
 *
 * @since 06/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Sitemap;


interface SitemapInterface
{
    public function getPriority();

    public function getChangeFrequency();

    public function getUpdated();
}