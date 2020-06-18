<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 23:22
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Form;

use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Form\Type\BlocksType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\PreloadedExtension;

class PreloadExtensionFactory
{
    public static function createBlocksTypeExtension(TestCase $testCase, $forms = [])
    {
        $blocks = [];
        foreach($forms as $form) {
            $block = $testCase->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();
            $block->method('getForm')->willReturn($form);
            $blocks[] = $block;
        }

        $blockManager = $testCase->getMockBuilder(BlockManager::class)->disableOriginalConstructor()->getMock();
        $blockManager->method('getBlocks')->willReturn($blocks);

        return new PreloadedExtension([new BlocksType($blockManager)], []);
    }
}
