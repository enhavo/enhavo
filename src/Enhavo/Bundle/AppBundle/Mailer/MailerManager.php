<?php


namespace Enhavo\Bundle\AppBundle\Mailer;

use Enhavo\Bundle\AppBundle\Exception\MailNotFoundException;
use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;
use Symfony\Component\HttpFoundation\File\File;
use Twig\Environment;

class MailerManager
{
    /** @var Swift_Mailer */
    private $mailer;

    /** @var TemplateManager */
    private $templateManager;

    /** @var Environment */
    private $environment;

    /** @var array */
    private $defaultConfig;

    /** @var array */
    private $mailsConfig;

    /** @var string */
    private $model;

    /**
     * MailerManager constructor.
     * @param Swift_Mailer $mailer
     * @param TemplateManager $templateManager
     * @param Environment $environment
     * @param array $defaultConfig
     * @param array $mailsConfig
     * @param string $model
     */
    public function __construct(Swift_Mailer $mailer, TemplateManager $templateManager, Environment $environment, array $defaultConfig, array $mailsConfig, string $model)
    {
        $this->mailer = $mailer;
        $this->templateManager = $templateManager;
        $this->environment = $environment;
        $this->defaultConfig = $defaultConfig;
        $this->mailsConfig = $mailsConfig;
        $this->model = $model;
    }


    public function sendMail(string $key, $resource, array $attachments = []): int
    {
        $message = $this->createMail($key, $resource, $attachments);
        return $this->sendMessage($message);
    }

    public function createMail(string $key, $resource, array $attachments = []): Message
    {
        if (!isset($this->mailsConfig[$key])) {
            throw new MailNotFoundException(sprintf('Mail with key "%s" does not exist in enhavo_app.mailer.mails', $key));
        }

        $message = $this->createMessage();

        $message->setFrom($this->renderString($this->mailsConfig[$key]['from'] ?? $this->defaultConfig['from'], ['resource' => $resource]));
        $message->setTo($this->renderString($this->mailsConfig[$key]['to'] ?? $this->defaultConfig['to'], ['resource' => $resource]));
        $message->setSenderName($this->renderString($this->mailsConfig[$key]['name'] ?? $this->defaultConfig['name'], ['resource' => $resource]));

        $message->setSubject($this->renderString($this->mailsConfig[$key]['subject'], ['resource' => $resource]));
        $message->setTemplate($this->mailsConfig[$key]['template']);
        $message->setContext([
            'resource' => $resource,
        ]);
        $message->setAttachments($attachments);
        $message->setContentType($this->mailsConfig[$key]['content_type']);

        return $message;
    }

    public function createMessage(): Message
    {
        return new $this->model;
    }

    public function sendMessage(Message $message): int
    {
        $swiftMessage = $this->convert($message);
        return $this->send($swiftMessage);
    }

    public function convert(Message $message): Swift_Message
    {
        $swiftMessage = new Swift_Message();
        $swiftMessage->setSubject($message->getSubject())
            ->setFrom(
                $message->getFrom(),
                $message->getSenderName()
            )
            ->setTo($message->getTo())
        ;

        $template = $this->environment->load($this->templateManager->getTemplate($message->getTemplate()));

        if ($message->getContentType() === Message::CONTENT_TYPE_MIXED) {
            $swiftMessage->setBody($template->renderBlock('text_plain', $message->getContext()), Message::CONTENT_TYPE_PLAIN);
            $swiftMessage->addPart($template->renderBlock('text_html', $message->getContext()), Message::CONTENT_TYPE_HTML);
        } else {
            $swiftMessage->setBody($template->render($message->getContext()), $message->getContentType());
        }

        foreach ($message->getAttachments() as $attachment) {
            if ($attachment instanceof FileInterface) {
                $swiftMessage->attach(
                    Swift_Attachment::fromPath($attachment->getContent()->getFilePath())
                        ->setFilename($attachment->getFilename())
                );
            } else if ($attachment instanceof File) {
                $swiftMessage->attach(Swift_Attachment::fromPath($attachment->getRealPath()));
            } else {
                $swiftMessage->attach(Swift_Attachment::fromPath($attachment));
            }
        }

        return $swiftMessage;
    }

    public function send(Swift_Message $message): int
    {
        return $this->mailer->send($message);
    }

    private function renderString(string $string, array $context)
    {
        return $this->environment->createTemplate($string)->render($context);
    }
}
