<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\DownloadActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Tests\Action\Type\BaseActionTypeFactoryTrait;
use PHPUnit\Framework\TestCase;

class DownloadActionTypeTest extends TestCase
{
    use UrlActionTypeFactoryTrait;
    use BaseActionTypeFactoryTrait;

    private function createDependencies(): DownloadActionTypeDependencies
    {
        $dependencies = new DownloadActionTypeDependencies();

        return $dependencies;
    }

    private function createInstance(DownloadActionTypeDependencies $dependencies): DownloadActionType
    {
        return new DownloadActionType();
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            $this->createBaseActionType($this->createBaseActionTypeDependencies()),
            $this->createUrlActionType($this->createUrlActionTypeDependencies()),
        ], [
            'route' => 'download_route',
        ]);

        $viewData = $action->createViewData();

        $this->assertFalse($viewData['ajax']);
        $this->assertEquals('/download_route', $viewData['url']);
    }

    public function testName()
    {
        $this->assertEquals('download', DownloadActionType::getName());
    }
}

class DownloadActionTypeDependencies
{
}
