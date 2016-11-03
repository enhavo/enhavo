<?php
/**
 * AbstractItemFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Item\ItemFactoryInterface;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class AbstractItemFactory implements ItemFactoryInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $dataClass;

    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function create()
    {
        return new $this->dataClass;
    }

    public function duplicate(ItemTypeInterface $original)
    {
        return new $this->dataClass;
    }

    public function getFileFactory()
    {
        return $this->container->get('enhavo_media.factory.file');
    }
}