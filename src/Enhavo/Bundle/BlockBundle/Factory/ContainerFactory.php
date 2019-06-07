<?php
namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Entity\Container;
use Enhavo\Bundle\BlockBundle\Entity\Block;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ContainerFactory extends Factory
{
    use ContainerAwareTrait;

    /**
     * @var blockTypeFactory
     */
    private $blockTypeFactory;


    /**
     * @var BlockFactory
     */
    private $blockFactories = [];


    public function __construct($className, BlockTypeFactory $blockTypeFactory)
    {
        $this->blockTypeFactory = $blockTypeFactory;
        parent::__construct($className);
    }

    /**
     * @param Container|null $originalResource
     * @return Container
     */
    public function duplicate($originalResource)
    {
        if (!$originalResource) {
            return null;
        }

        /** @var Container $newContainer */
        $newContainer = $this->createNew();

        /** @var Block $block */
        foreach($originalResource->getBlocks() as $block) {
            $newBlock = $this->getBlockFactory($block->getName())->duplicate($block->getBlockType());
            $newContainer->addBlock($newBlock);
        }

        return $newContainer;
    }

    private function getBlockFactory($name)
    {
        if(isset($this->blockFactories[$name])) {
            return $this->blockFactories[$name];
        }

        $this->blockFactories[$name] = new BlockFactory($this->getBlockTypeFactory(), $name);
        return $this->blockFactories[$name];
    }

    private function getBlockTypeFactory()
    {
        return $this->blockTypeFactory;
    }
}
