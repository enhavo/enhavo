<?php
/**
 * BlockResolver.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AdminBundle\Block;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BlockFactory implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param $type
     * @return BlockInterface
     */
    public function create($type)
    {
        return new TableBlock($this->container->get('templating'));
    }
}