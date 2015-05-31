<?php
/**
 * ViewerFactoryTest.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Test\Viewer;

use esperanto\AdminBundle\Viewer\ViewerFactory;
use esperanto\AdminBundle\Exception\AppViewer;

class ViewerFactoryTest
{
    public function testCreate()
    {
        $containerMock = $this->getContainerMock();
        $factory = new ViewerFactory($containerMock);

        $viewer = $factory->create($this->getRequestMock('viewer.app'));

        $this->assertEquals($containerMock, $viewer->getContainer());
        $this->assertTrue($viewer instanceof AppViewer);
    }

    protected function getContainerMock()
    {

    }

    protected function getRequestMock($viewer)
    {

    }
}