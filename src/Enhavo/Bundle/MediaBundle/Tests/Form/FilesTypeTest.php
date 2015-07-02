<?php
/**
 * FilesTypeTest.php
 *
 * @since 16/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\PageBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\MediaBundle\Form\Type\FilesType;
use Enhavo\Bundle\TestingBundle\Doctrine\ObjectMangerMock;
use Symfony\Component\Form\Test\TypeTestCase;

class FileTypeTest extends TypeTestCase
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
        $om->getRepository('enhavoMediaBundle:File')->add($this->generateFileEntityObject(1));
        $om->getRepository('enhavoMediaBundle:File')->add($this->generateFileEntityObject(2));
        return $om;
    }

    public function testSubmitValidData()
    {
        $formData = array(1, 2);

        $type = new FilesType($this->getObjectManagerMock());
        $form = $this->factory->create($type);

        $data = new ArrayCollection();
        $form->setData($data);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        /** @var $data ArrayCollection */
        $data = $form->getData();

        $this->assertEquals($data->get(0)->getId(), 1);
        $this->assertEquals($data->get(1)->getId(), 2);
    }
} 