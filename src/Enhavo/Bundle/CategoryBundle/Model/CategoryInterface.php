<?php

/**
 * CategoryInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CategoryBundle\Model;

interface CategoryInterface
{
    /**
     * Set collection
     *
     * @param CollectionInterface $collection
     * @return CategoryInterface
     */
    public function setCollection(CollectionInterface $collection = null);

    /**
     * Get collection
     *
     * @return CollectionInterface
     */
    public function getCollection();
}