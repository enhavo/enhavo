<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Form;

use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockCollectionType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\PreloadedExtension;

class PreloadExtensionFactory
{
    public static function createBlockCollectionTypeExtension(TestCase $testCase, $blocks = [])
    {
        $blockManager = $testCase->getMockBuilder(BlockManager::class)->disableOriginalConstructor()->getMock();
        $blockManager->method('getBlocks')->willReturn($blocks);

        return new PreloadedExtension([new BlockCollectionType($blockManager)], []);
    }
}
