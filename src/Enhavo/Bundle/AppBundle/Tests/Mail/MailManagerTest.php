<?php

namespace Enhavo\Bundle\AppBundle\Tests\Mail;

use Enhavo\Bundle\AppBundle\Exception\MailNotFoundException;
use Enhavo\Bundle\AppBundle\Mail\MailManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TemplateWrapper;

class MailManagerTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new MailManagerTestDependencies();
        $dependencies->environment = new Environment(new FilesystemLoader([
            'Fixtures/BackupBundle/Mail/MailManager',
        ], __DIR__ . '/../'));
//        $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $dependencies->mailer = $this->getMockBuilder(\Swift_Mailer::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    private function createInstance(MailManagerTestDependencies $dependencies, array $config)
    {
        return new MailManager($dependencies->mailer, $dependencies->environment, $config);
    }

    public function testSendMailMultipart()
    {
        $dependencies = $this->createDependencies();
        $config = [
            'default' => [
                'subject' => '{{ resource.subject }}',
                'from' => '{{ resource.from }}',
                'name' => '{{ resource.name }}',
                'to' => '{{ resource.to }}',
                'template' => 'multipart-mail.html.twig',
                'content_type' => MailManager::CONTENT_TYPE_MIXED,
            ]
        ];

        $manager = $this->createInstance($dependencies, $config);

        $dependencies->mailer->expects($this->once())->method('send')->willReturnCallback(function (\Swift_Message $message) {
            $this->assertEquals('__subject__', $message->getSubject());
            $this->assertEquals([
                'from@enhavo.com' => '__name__'
            ], $message->getFrom());
            $this->assertEquals([
                'to@enhavo.com' => null
            ], $message->getTo());
            $this->assertEquals('__text__', $message->getBody());
            $this->assertEquals(MailManager::CONTENT_TYPE_PLAIN, $message->getBodyContentType());

        });

        $manager->sendMail('default', [
            'text' => '__text__',
            'name' => '__name__',
            'subject' => '__subject__',
            'resource' => '__RESOURCE__',
            'from' => 'from@enhavo.com',
            'to' => 'to@enhavo.com'
        ], [
            __DIR__ . '/../Fixtures/BackupBundle/Mail/MailManager/dummy-attachment.txt'
        ]);
    }

    public function testSendMailSimple()
    {
        $dependencies = $this->createDependencies();
        $config = [
            'default' => [
                'subject' => '{{ resource.subject }}',
                'from' => '{{ resource.from }}',
                'name' => '{{ resource.name }}',
                'to' => '{{ resource.to }}',
                'template' => 'simple-mail.html.twig',
                'content_type' => MailManager::CONTENT_TYPE_PLAIN,
            ]
        ];

        $manager = $this->createInstance($dependencies, $config);

        $dependencies->mailer->expects($this->once())->method('send')->willReturnCallback(function (\Swift_Message $message) {
            $this->assertEquals('__text__', $message->getBody());
            $this->assertEquals(MailManager::CONTENT_TYPE_PLAIN, $message->getBodyContentType());

        });

        $manager->sendMail('default', [
            'text' => '__text__',
            'name' => '__name__',
            'subject' => '__subject__',
            'resource' => '__RESOURCE__',
            'from' => 'from@enhavo.com',
            'to' => 'to@enhavo.com'
        ]);

        $this->expectException(MailNotFoundException::class);

        $manager->sendMail('uknown', null);
    }
}

class MailManagerTestDependencies
{
    /** @var Environment|MockObject */
    public $environment;
    /** @var \Swift_Mailer|MockObject */
    public $mailer;
}
