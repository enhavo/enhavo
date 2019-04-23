<?php
/**
 * AbstractItemFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\FormBundle\DynamicForm\FactoryInterface;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class AbstractItemFactory implements ContainerAwareInterface, FactoryInterface
{
    use ContainerAwareTrait;

    protected $dataClass;

    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function createNew()
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