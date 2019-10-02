<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-02
 * Time: 14:17
 */

namespace Enhavo\Bundle\FormBundle\Helper;

use Enhavo\Bundle\FormBundle\Form\Helper\EntrypointFileManager;
use PHPUnit\Framework\TestCase;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollectionInterface;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;

class EntrypointFileManagerTest extends TestCase
{
    public function testGetCssFiles()
    {
        $firstCall = true;
        $entrypontLookupMock = $this->getMockBuilder(EntrypointLookupInterface::class)->getMock();
        $entrypontLookupMock->method('getCssFiles')->willReturnCallback(function() use($firstCall) {
            if($firstCall) {
                return ['entrypoint_css'];
            }
            return ['other_file'];
        });

        $entrypontLookupCollectionMock = $this->getMockBuilder(EntrypointLookupCollectionInterface::class)->getMock();
        $entrypontLookupCollectionMock->method('getEntrypointLookup')->willReturnCallback(function() use ($entrypontLookupMock) {
            return $entrypontLookupMock;
        });

        $manager = new EntrypointFileManager($entrypontLookupCollectionMock);
        $files = $manager->getCssFiles('entrypoint', 'build');
        self::assertEquals('entrypoint_css', $files[0]);

        // test cache
        $files = $manager->getCssFiles('entrypoint', 'build');
        self::assertEquals('entrypoint_css', $files[0]);
    }
}
