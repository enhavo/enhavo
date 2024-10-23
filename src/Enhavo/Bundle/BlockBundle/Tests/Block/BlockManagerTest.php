<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-07-03
 * Time: 17:58
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Block;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Block\Cleaner;
use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;
use Enhavo\Component\Type\Factory;
use Enhavo\Component\Type\FactoryInterface;
use Enhavo\Component\Type\Tests\Mock\RegistryMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BlockManagerTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new BlockManagerTestDependencies();
        $dependencies->associationFinder = $this->getMockBuilder(AssociationFinder::class)->disableOriginalConstructor()->getMock();
        $dependencies->cleaner = $this->getMockBuilder(Cleaner::class)->disableOriginalConstructor()->getMock();
        $dependencies->registry = new RegistryMock();
        $dependencies->factory = new Factory(Block::class, $dependencies->registry);
        $dependencies->configurations = [];
        return $dependencies;
    }

    private function createInstance(BlockManagerTestDependencies $dependencies)
    {
        return new BlockManager($dependencies->factory, $dependencies->associationFinder, $dependencies->cleaner, $dependencies->configurations);
    }

    public function testGetter()
    {
        $dependencies = $this->createDependencies();
        $dependencies->registry->register('text', new BlockManagerTextBlockType());
        $dependencies->registry->register('more_text', new BlockManagerTextBlockType());
        $dependencies->configurations = [
            'text' => [
                'type' => 'text',
            ],
            'text2' => [
                'type' => 'more_text',
            ]
        ];
        $manager = $this->createInstance($dependencies);

        $blocks = $manager->getBlocks();

        $this->assertCount(2, $blocks);
        $this->assertArrayHasKey('text', $blocks);
        $this->assertArrayHasKey('text2', $blocks);

        $block = $manager->getBlock('text');
        $this->assertNotNull($block);
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->registry->register('text', new BlockManagerTextBlockType());
        $dependencies->configurations = [
            'text' => [
                'type' => 'text',
            ],
        ];
        $manager = $this->createInstance($dependencies);

        $childBlock = new BlockManagerTextBlock();
        $childNode = new Node();
        $childNode->setName('text');
        $childNode->setBlock($childBlock);
        $childNode->setType(NodeInterface::TYPE_BLOCK);

        $block = new BlockManagerTextBlock();
        $node = new Node();
        $node->setName('text');
        $node->setBlock($block);
        $node->setType(NodeInterface::TYPE_BLOCK);
        $node->addChild($childNode);


        $manager->createViewData($node);

        $this->assertEquals([
            'foo' => 'bar',
            'hello' => 'world'
        ], $node->getViewData());

        $this->assertEquals([
            'foo' => 'bar',
            'hello' => 'world'
        ], $node->getChildren()[0]->getViewData());
    }
}

class BlockManagerTestDependencies
{
    public FactoryInterface|MockObject $factory;
    public AssociationFinder|MockObject $associationFinder;
    public Cleaner|MockObject $cleaner;
    public array $configurations;
    public RegistryMock|MockObject $registry;
}

class BlockManagerTextBlockType extends AbstractBlockType
{
    public static function getParentType(): ?string
    {
        return null;
    }

    public function createViewData(BlockInterface $block, Data $data, $resource, array $options)
    {
        $data['foo'] = 'bar';
    }

    public function finishViewData(BlockInterface $block, Data $data, $resource, array $options)
    {
        $data['hello'] = 'world';
    }
}

class BlockManagerTextBlock implements BlockInterface
{
    /** @var NodeInterface */
    private $node;

    /**
     * @return NodeInterface
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param NodeInterface $node
     */
    public function setNode(NodeInterface $node)
    {
        $this->node = $node;
    }
}
