<?php
/**
 * BlockResolver.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Block;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Enhavo\Bundle\SearchBundle\Block\SearchBlock;

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

    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param $type
     * @return BlockInterface
     */
    public function create($type)
    {
        if($type == 'enhavo_table'){
            return new TableBlock($this->container->get('templating'));
        } else {
            return new SearchBlock($this->container->get('templating'));
        }

    }
}