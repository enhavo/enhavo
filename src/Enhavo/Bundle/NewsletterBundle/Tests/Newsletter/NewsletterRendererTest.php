<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-03
 * Time: 17:06
 */

namespace Enhavo\Bundle\NewsletterBundle\Tests\Newsletter;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterRenderer;
use Enhavo\Bundle\NewsletterBundle\Newsletter\ParameterParserInterface;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class NewsletterRendererTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new Dependencies();
        $dependencies->templates = [
            'default' => [
                'template' => 'pathToDefaultTemplate.twig',
                'label' => 'Default Template'
            ]
        ];
        $dependencies->templateManager = $this->getMockBuilder(TemplateManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->environment = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $dependencies->parameterParser = $this->getMockBuilder(ParameterParserInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(Dependencies $dependencies)
    {
        return new NewsletterRenderer(
            $dependencies->environment,
            $dependencies->templateManager,
            $dependencies->parameterParser,
            $dependencies->templates
        );
    }

    public function testRender()
    {
        $dependencies = $this->createDependencies();
        $dependencies->environment->method('render')->willReturn('content');
        $dependencies->parameterParser->method('parse')->willReturnCallback(function ($content) { return $content; });
        $newsletterRenderer = $this->createInstance($dependencies);

        $newsletter = new Newsletter();
        $receiver = new Receiver();
        $receiver->setNewsletter($newsletter);
        $receiver->setParameters(['param1' => 'someValue']);

        $content = $newsletterRenderer->render($receiver);
        $this->assertEquals('content', $content);
    }

    public function testWithTemplateKey()
    {
        $dependencies = $this->createDependencies();
        $dependencies->environment->method('render')->willReturnCallback(function ($template, $parameters) { return $template; });
        $dependencies->templateManager->method('getTemplate')->willReturnCallback(function ($template) { return $template; });
        $dependencies->templates['other'] = [
            'template' => 'pathToOtherTemplate.twig',
            'label' => 'Other Template'
        ];
        $newsletterRenderer = $this->createInstance($dependencies);

        $newsletter = new Newsletter();
        $newsletter->setTemplate('other');
        $receiver = new Receiver();
        $receiver->setNewsletter($newsletter);

        $content = $newsletterRenderer->render($receiver);
        $this->assertEquals('pathToOtherTemplate.twig', $content);
    }

    /**
     *
     */
    public function testWithoutTemplateKey()
    {
        $dependencies = $this->createDependencies();
        $dependencies->templates['other'] = [
            'template' => 'pathToOtherTemplate.twig',
            'label' => 'Other Template'
        ];
        $newsletterRenderer = $this->createInstance($dependencies);

        $newsletter = new Newsletter();
        $receiver = new Receiver();
        $receiver->setNewsletter($newsletter);

        $this->expectException(\Exception::class);
        $newsletterRenderer->render($receiver);
    }
}

class Dependencies
{
    /** @var Environment|\PHPUnit_Framework_MockObject_MockObject */
    public $environment;
    /** @var TemplateManager|\PHPUnit_Framework_MockObject_MockObject */
    public $templateManager;
    /** @var ParameterParserInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $parameterParser;
    public $templates;
}
