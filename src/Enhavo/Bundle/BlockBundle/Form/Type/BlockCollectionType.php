<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:42
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockCollectionType extends AbstractType
{
    /** @var BlockManager */
    private $blockManager;

    public function __construct(BlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'block.label.blocks',
            'translation_domain' => 'EnhavoBlockBundle',
            'entry_types' => $this->getEntryTypes(),
            'entry_types_options' => $this->getEntryTypesOptions(),
            'entry_type_name' => 'name',
            'entry_type_resolver' => function(Node $node) {
                return $node->getName();
            },
            'allow_add' => true,
            'allow_delete' => true,
            'item_groups' => [],
            'items' => [],
            'prototype_storage' => 'enhavo_block'
        ]);
    }

    private function getEntryTypes()
    {
        $types = [];
        /** @var Block $block */
        foreach($this->blockManager->getBlocks() as $key => $block) {
            $types[$key] = NodeType::class;
        }
        return $types;
    }

    private function getEntryTypesOptions()
    {
        $types = [];
        /** @var Block $block */
        foreach($this->blockManager->getBlocks() as $key => $block) {
            $types[$key] = [
                'block_type' => $block->getForm(),
                'label' => $block->getLabel(),
                'translation_domain' => $block->getTranslationDomain(),
            ];
        }
        return $types;
    }

    public function getParent()
    {
        return PolyCollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_blocks';
    }
}
