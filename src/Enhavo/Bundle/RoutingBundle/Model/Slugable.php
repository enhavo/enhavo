<?php
/**
 * Slugable.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Routing;

interface Slugable
{
    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $slug
     * @return void
     */
    public function setSlug($slug);
}