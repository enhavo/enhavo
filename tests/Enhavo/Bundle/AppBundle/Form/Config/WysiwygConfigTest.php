<?php

namespace Enhavo\Bundle\AppBundle\Config;

use Enhavo\Bundle\AppBundle\Form\Config\WysiwygConfig;
use Enhavo\Bundle\AppBundle\Form\Config\WysiwygOption;

class WysiwygConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $config = new WysiwygConfig();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Form\Config\WysiwygConfig', $config);
    }

    public function testGetData()
    {
        $config = new WysiwygConfig();
        $option = new WysiwygOption();
        $data = $config->getData($option);
        $this->assertJson($data);

        $data = json_decode($data, true);
        $this->assertArrayHasKey('formats', $data);
        $this->assertArrayHasKey('toolbar1', $data);
        $this->assertArrayHasKey('toolbar2', $data);
        $this->assertArrayHasKey('height', $data);
        $this->assertArrayHasKey('style_formats', $data);
        $this->assertArrayHasKey('content_css', $data);
    }
}