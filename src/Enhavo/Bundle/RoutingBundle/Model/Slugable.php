<?php
/**
 * Slugable.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Model;

interface Slugable
{
    public function getSlug(): ?string;

    public function setSlug(?string $slug);
}
