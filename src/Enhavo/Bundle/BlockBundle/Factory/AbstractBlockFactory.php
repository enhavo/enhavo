<?php
/**
 * AbstractBlockFactory.php
 *
 * @since 02/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class AbstractBlockFactory implements ContainerAwareInterface, BlockFactoryInterface
{
    use ContainerAwareTrait;

    public function __construct(
        protected string $dataClass
    ) {
    }

    public function createNew()
    {
        return new $this->dataClass;
    }

    public function duplicate(BlockInterface $original)
    {
        return clone $original;
    }

    public function getFileFactory()
    {
        return $this->container->get('enhavo_media.file.factory');
    }
}
