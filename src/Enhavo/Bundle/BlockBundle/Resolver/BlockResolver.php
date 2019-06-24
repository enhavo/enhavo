<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.08.18
 * Time: 17:57
 */

namespace Enhavo\Bundle\BlockBundle\Resolver;

use Enhavo\Bundle\AppBundle\Exception\ResolverException;
use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Factory\BlockFactory;
use Enhavo\Bundle\BlockBundle\Factory\NodeFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockType;
use Enhavo\Bundle\FormBundle\DynamicForm\ItemInterface;
use Enhavo\Bundle\FormBundle\DynamicForm\ResolverInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormFactoryInterface;

class BlockResolver implements ResolverInterface
{
    use ContainerAwareTrait;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @var Block[]
     */
    private $blocks = [];

    public function __construct(FormFactoryInterface $formFactory, BlockFactory $blockFactory, BlockManager $blockManager)
    {
        $this->formFactory = $formFactory;
        $this->blockFactory = $blockFactory;
        $this->blocks = $blockManager->getBlocks();
    }

    /**
     * @param string[] $groups
     * @return ItemInterface[]
     */
    public function resolveItemGroup($groups = [])
    {
        $blocks = [];
        foreach($this->blocks as $block) {
            foreach($groups as $group) {
                if(in_array($group, $block->getGroups())) {
                    if(!in_array($block, $blocks)) {
                        $blocks[] = $block;
                    }
                }
            }
        }
        return $blocks;
    }

    /**
     * @param $name
     * @return Block
     * @throws \Exception
     */
    public function resolveItem($name)
    {
        if(!array_key_exists($name, $this->blocks)) {
            throw new ResolverException(sprintf('ContainerBlock with name "%s" does not exist', $name));
        }

        return $this->blocks[$name];
    }

    public function resolveDefaultItems()
    {
        return array_values($this->blocks);
    }

    public function resolveForm($name, $data = null, $options = [])
    {
        $block = $this->resolveItem($name);

        $formOptions = [
            'item_type_form' => $block->getForm(),
            'item_type_parameters' => isset($options['item_type_parameters']) ?: [],
            'item_resolver' => 'enhavo_block.resolver.block_resolver',
            'item_property' => 'name',
        ];

        $form = $this->formFactory->create(BlockType::class, $data, array_merge($formOptions, $options));
        return $form;
    }

    public function resolveFactory($name)
    {
        $block = $this->resolveItem($name);
        return new NodeFactory($this->blockFactory, $name);
    }

    public function resolveFormTemplate($name)
    {
        $block = $this->resolveItem($name);
        return $block->getFormTemplate();
    }
}
