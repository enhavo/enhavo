<?php
/**
 * FactoryInterface.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item;

interface ItemFactoryInterface
{
    public function create();

    public function duplicate(ItemTypeInterface $original);
}