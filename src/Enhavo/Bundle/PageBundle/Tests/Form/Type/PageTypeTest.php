<?php
/**
 * PageTypeTest.php
 *
 * @since 16/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\PageBundle\Tests\Form\Type;

use Enhavo\Bundle\MediaBundle\Form\Type\FilesType;
use Enhavo\Bundle\PageBundle\Form\Type\PageType;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Enhavo\Bundle\TestingBundle\Doctrine\ObjectMangerMock;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;

class PageTypeTest  extends TypeTestCase
{
    protected function generateFileEntityObject($id)
    {
        $mock = $this->getMock('\Enhavo\Bundle\MediaBundle\Entity\File', array(
            'getId'
        ));
        $mock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        return $mock;
    }

    protected function getObjectManagerMock()
    {
        $om = new ObjectMangerMock();
        $om->getRepository('EnhavoMediaBundle:File')->add($this->generateFileEntityObject(1));
        $om->getRepository('EnhavoMediaBundle:File')->add($this->generateFileEntityObject(2));
        return $om;
    }

    protected function getExtensions()
    {
        $childType = new FilesType($this->getObjectManagerMock());
        return array(new PreloadedExtension(array(
            $childType->getName() => $childType,
        ), array()));
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'title' => 'hello',
            'files' => array(1, 2)
        );

        $type = new PageType();
        $form = $this->factory->create($type);

        $data = new Page();
        //$data->setTitle('before');
        //$data->addFile($this->generateFileEntityObject(1));

        $form->setData($data);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        /** @var $data Page */
        $data = $form->getData();

        $this->assertEquals($data->getTitle(), 'hello');
        $this->assertFalse($data->getFiles()->isEmpty());
        $this->assertEquals($data->getFiles()->get(0)->getId(), 1);
        $this->assertEquals($data->getFiles()->get(1)->getId(), 2);
    }
} 