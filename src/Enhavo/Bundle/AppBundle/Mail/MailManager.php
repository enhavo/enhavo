<?php


namespace Enhavo\Bundle\AppBundle\Mail;


use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\File\File;
use Twig\Environment;

class MailManager
{
    /** @var Swift_Mailer */
    private $mailer;

    /** @var Environment */
    private $environment;

    /**
     * @var array
     */
    private $config;

    /**
     * MailManager constructor.
     * @param Swift_Mailer $mailer
     * @param Environment $environment
     * @param array $config
     */
    public function __construct(Swift_Mailer $mailer, Environment $environment, array $config)
    {
        $this->mailer = $mailer;
        $this->environment = $environment;
        $this->config = $config;
    }

    public function sendMail(string $namespace, $resource, array $attachments = [])
    {
        $vars = [
            'resource' => $resource,
        ];

        $message = $this->createMessage(
            $this->config[$namespace]['from'],
            $this->config[$namespace]['name'],
            $this->config[$namespace]['to'],
            $this->config[$namespace]['subject'],
            $this->config[$namespace]['template'],
            $vars,
            $attachments,
            $this->config[$namespace]['content_type']
        );

        return $this->mailer->send($message);
    }

    /**
     * @param string $from
     * @param string $senderName
     * @param string $to
     * @param string $subject
     * @param string $template
     * @param array $context
     * @param array|FileInterface[]|File[] $attachments
     * @param string $contentType
     * @return Swift_Message
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function createMessage(string $from, string $senderName, string $to, string $subject, string $template, array $context, array $attachments = [], string $contentType = 'text/plain'): \Swift_Message
    {
        $message = new Swift_Message();
        $message->setSubject($this->renderString($subject, $context))
            ->setFrom(
                $this->renderString($from, $context),
                $this->renderString($senderName, $context)
            )
            ->setTo($this->renderString($to, $context))
        ;

        $template = $this->environment->load($template);

        try {
            $textPlain = $template->renderBlock('text_plain', $context);
            $textHtml = $template->renderBlock('text_html', $context);
        } catch (\Throwable $e) {
            $body = $template->render($context);
        }

        if (isset($body)) {
            $message->setBody($body, $contentType);

        } else {
            if (isset($textPlain)) {
                $message->setBody($textPlain, 'text/plain');
            }
            if (isset($textHtml)) {
                $message->addPart($textHtml, 'text/html');
            }
        }

        foreach ($attachments as $attachment) {
            if ($attachment instanceof FileInterface) {
                $message->attach(\Swift_Attachment::fromPath($attachment->getFilename()));
            } else if ($attachment instanceof File) {
                $message->attach(\Swift_Attachment::fromPath($attachment->getRealPath()));
            } else {
                $message->attach(\Swift_Attachment::fromPath($attachment));
            }
        }

        return $message;
    }

    private function renderString(string $string, array $context)
    {
        return $this->environment->createTemplate($string)->render($context);
    }
}
