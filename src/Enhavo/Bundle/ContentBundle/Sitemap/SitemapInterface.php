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
<<<<<<< HEAD
}
=======

    public function isNoIndex();
}
>>>>>>> 231e75f31 ([ContentBundle] Fixed resources with noindex flag showing up in sitemap (#1396))
