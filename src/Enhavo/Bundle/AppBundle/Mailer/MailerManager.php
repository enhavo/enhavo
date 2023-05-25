<?php


namespace Enhavo\Bundle\AppBundle\Mailer;

use Enhavo\Bundle\AppBundle\Exception\MailAttachmentException;
use Enhavo\Bundle\AppBundle\Exception\MailNotFoundException;
use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class MailerManager
{
    private Defaults $defaults;

    public function __construct(
        private MailerInterface $mailer,
        private TemplateManager $templateManager,
        private Environment $environment,
        array $defaultConfig,
        private array $mailsConfig,
        private string $model,
        private TranslatorInterface $translator,
    ) {
        $this->defaults = new Defaults($defaultConfig['from'], $defaultConfig['name'], $defaultConfig['to']);
    }

    public function sendMail(string $key, $resource, array $attachments = []): void
    {
        $message = $this->createMail($key, $resource, $attachments);
        $this->sendMessage($message);
    }

    public function createMail(string $key, $resource, array $attachments = []): Message
    {
        if (!isset($this->mailsConfig[$key])) {
            throw new MailNotFoundException(sprintf('Mail with key "%s" does not exist in enhavo_app.mailer.mails', $key));
        }

        $message = $this->createMessage();

        $message->setTo($this->renderString($this->mailsConfig[$key]['to'] ?? $this->defaults->getMailTo(), ['resource' => $resource]));
        foreach ($this->getCopies($key) as $email) {
            $message->addCc($this->renderString($email, ['resource' => $resource]));
        }
        foreach ($this->getBlindCopies($key) as $email) {
            $message->addBcc($this->renderString($email, ['resource' => $resource]));
        }

        $message->setFrom($this->renderString($this->mailsConfig[$key]['from'] ?? $this->defaults->getMailFrom(), ['resource' => $resource]));

        $senderName = $this->renderString($this->mailsConfig[$key]['name'] ?? $this->defaults->getMailSenderName(), ['resource' => $resource]);
        $message->setSenderName($this->translator->trans($senderName, [], $this->mailsConfig[$key]['translation_domain']));

        $subject = $this->renderString($this->mailsConfig[$key]['subject'], ['resource' => $resource]);
        $message->setSubject($this->translator->trans($subject, [], $this->mailsConfig[$key]['translation_domain']));

        $message->setTemplate($this->mailsConfig[$key]['template']);
        $message->setContext([
            'resource' => $resource,
        ]);

        foreach ($attachments as $attachment) {
            $message->addAttachment($attachment);
        }

        $message->setContentType($this->mailsConfig[$key]['content_type']);

        return $message;
    }

    private function getCopies(string $key): array
    {
        $ccs = [];
        if (is_string($this->mailsConfig[$key]['cc'])) {
            $ccs[] = trim($this->mailsConfig[$key]['cc']);
        } else if (is_array($this->mailsConfig[$key]['cc'])) {
            foreach ($this->mailsConfig[$key]['cc'] as $cc) {
                $ccs[] = trim($cc);
            }
        }
        return $ccs;
    }

    private function getBlindCopies(string $key): array
    {
        $bcs = [];
        if (is_string($this->mailsConfig[$key]['bcc'])) {
            $bcs[] = trim($this->mailsConfig[$key]['bcc']);
        } else if (is_array($this->mailsConfig[$key]['bcc'])) {
            foreach ($this->mailsConfig[$key]['bcc'] as $cc) {
                $bcs[] = trim($cc);
            }
        }
        return $bcs;
    }

    public function createMessage(): Message
    {
        return new $this->model;
    }

    public function sendMessage(Message $message): void
    {
        $email = $this->convert($message);
        $this->send($email);
    }

    public function convert(Message $message): Email
    {
        $email = new Email();
        $email->subject($message->getSubject())
            ->from(new Address($message->getFrom(), $message->getSenderName()))
            ->to(new Address($message->getTo()))
        ;

        foreach ($message->getCc() as $cc) {
            $email->addCc(new Address($cc));
        }

        foreach ($message->getBcc() as $bcc) {
            $email->addBcc(new Address($bcc));
        }

        if ($message->getContent() === null) {
            $template = $this->environment->load($this->templateManager->getTemplate($message->getTemplate()));

            if ($message->getContentType() === Message::CONTENT_TYPE_MIXED) {
                $email->html($template->renderBlock('text_html', $message->getContext()));
                $email->text($template->renderBlock('text_plain', $message->getContext()));
            } else {
                $email->html($template->render($message->getContext()));
            }
        } else {
            $email->html($message->getContent());
        }

        foreach ($message->getAttachments() as $attachment)
        {
            $file = $attachment->getFile();

            // MediaBundle is maybe not installed
            if (is_subclass_of($file, 'Enhavo\Bundle\MediaBundle\Model\FileInterface')) {
                $email->attachFromPath($file->getContent()->getFilePath(), $attachment->getName() ?? $file->getFilename(), $attachment->getMimetype() ?? $file->getMimetype());
            } else if ($file instanceof File) {
                $email->attachFromPath($file->getRealPath());
            } else if (is_string($file)) {
                $email->attachFromPath($file);
            } else {
                throw new MailAttachmentException(sprintf('Attachment file type not supported. Give "%s"', is_object($file) ? get_class($file) : gettype($file)));
            }
        }

        return $email;
    }

    public function send(Email $message): void
    {
        $this->mailer->send($message);
    }

    private function renderString(string $string, array $context)
    {
        return $this->environment->createTemplate($string)->render($context);
    }

    public function getDefaults(): Defaults
    {
        return $this->defaults;
    }
}
