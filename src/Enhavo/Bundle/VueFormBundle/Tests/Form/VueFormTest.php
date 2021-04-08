<?php

namespace Enhavo\Bundle\VueFormBundle\Tests\Form;

use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use PHPUnit\Framework\TestCase;

class VueFormTest extends TestCase
{
    public function createInstance(VueFormDependencies $dependencies)
    {
        $instance = new VueForm();
        return $instance;
    }

    public function createDependencies()
    {
        $dependencies = new VueFormDependencies;
        return $dependencies;
    }

    public function testCreateData()
    {
        $dependencies = $this->createDependencies();
        $vueForm = $this->createInstance($dependencies);

        $this->assertTrue(true);
    }
}

class VueFormDependencies
{

}
