<?php


namespace Enhavo\Bundle\AppBundle\Mail;


use Enhavo\Bundle\AppBundle\Exception\MailNotFoundException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\File\File;
use Twig\Environment;

class MailManager
{
    const CONTENT_TYPE_MIXED = 'multipart/mixed';
    const CONTENT_TYPE_HTML = 'text/html';
    const CONTENT_TYPE_PLAIN = 'text/plain';

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

    public function sendMail(string $key, $resource, array $attachments = [])
    {
        if (!isset($this->config[$key])) {
            throw new MailNotFoundException(sprintf('Mail with key "%s" does not exist in enhavo_app.mails', $key));
        }

        $vars = [
            'resource' => $resource,
        ];

        $message = $this->createMessage(
            $this->config[$key]['from'],
            $this->config[$key]['name'],
            $this->config[$key]['to'],
            $this->config[$key]['subject'],
            $this->config[$key]['template'],
            $vars,
            $attachments,
            $this->config[$key]['content_type']
        );

        return $this->sendMessage($message);
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
    public function createMessage(string $from, string $senderName, string $to, string $subject, string $template, array $context, array $attachments = [], string $contentType = self::CONTENT_TYPE_PLAIN): \Swift_Message
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

        if ($contentType === self::CONTENT_TYPE_MIXED) {
            $message->setBody($template->renderBlock('text_plain', $context), self::CONTENT_TYPE_PLAIN);
            $message->addPart($template->renderBlock('text_html', $context), self::CONTENT_TYPE_HTML);
        } else {
            $message->setBody($template->render($context), $contentType);
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

    public function sendMessage(\Swift_Message $message)
    {
        return $this->mailer->send($message);
    }

    private function renderString(string $string, array $context)
    {
        return $this->environment->createTemplate($string)->render($context);
    }
}
