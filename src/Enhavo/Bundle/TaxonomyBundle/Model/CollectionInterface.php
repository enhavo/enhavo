<?php
/**
 * CollectionInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CategoryBundle\Model;


interface CollectionInterface
{
    /**
     * Set name
     *
     * @param string $name
     * @return CollectionInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();
}