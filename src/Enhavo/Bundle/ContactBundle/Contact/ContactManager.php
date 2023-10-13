<?php


namespace Enhavo\Bundle\ContactBundle\Contact;


use Enhavo\Bundle\AppBundle\Mailer\MailerManager;
use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\ContactBundle\Model\ContactInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ContactManager
{
    /**
     * @var array
     */
    private $forms;

    /**
     * @var array
     */
    private $mailerDefaults;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MailerManager
     */
    private $mailerManager;

    /**
     * @var TemplateResolver
     */
    private $templateResolver;

    public function __construct(
        array $forms,
        array $mailerDefaults,
        FormFactoryInterface $formFactory,
        MailerManager $mailerManager,
        TemplateResolver $templateResolver
    )
    {
        $this->forms = $forms;
        $this->mailerDefaults = $mailerDefaults;
        $this->formFactory = $formFactory;
        $this->mailerManager = $mailerManager;
        $this->templateResolver = $templateResolver;
    }

    public function submit(ContactInterface $contact, $key = 'default')
    {
        $configuration = $this->getConfiguration($key);

        $this->sendRecipientMail($contact, $configuration['recipient']);
        if (isset($configuration['confirm']['enable']) && $configuration['confirm']['enable']) {
            $this->sendConfirmMail($contact, $configuration['confirm']);
        }
    }

    private function sendRecipientMail(ContactInterface $contact, $options)
    {
        $message = $this->createMessage($contact, $options);
        $message->setTo($options['to'] ?? $this->mailerDefaults['to']);

        $this->mailerManager->sendMessage($message);
    }

    private function sendConfirmMail(ContactInterface $contact, $options)
    {
        $message = $this->createMessage($contact, $options);
        $message->setTo($contact->getEmail());

        $this->mailerManager->sendMessage($message);
    }

    private function createMessage(ContactInterface $contact, $options)
    {
        $message = $this->mailerManager->createMessage();

        $message->setFrom($options['from'] ?? $this->mailerDefaults['from']);
        $message->setSenderName($options['sender_name'] ?? $this->mailerDefaults['name']);

        $message->setSubject($options['subject']);
        $message->setTemplate($this->templateResolver->resolve($options['template']));
        $message->setContentType($options['content_type']);
        $message->setContext([
            'resource' => $contact,
        ]);

        return $message;
    }

    private function getConfiguration($key)
    {
        if (!isset($this->forms[$key])) {
            throw new \Exception(sprintf('Key "%s" not defined in forms.', $key));
        }
        return $this->forms[$key];
    }

    private function getOption($key, $option)
    {
        $configuration = $this->getConfiguration($key);
        if (!isset($configuration[$option])) {
            throw new \Exception(sprintf('Option "%s" not defined in configuration.', $key));
        }
        return $configuration[$option];
    }

    public function createForm($key = 'default')
    {
        $model = $this->getOption($key, 'model');

        return $this->formFactory->create(
            $this->getOption($key, 'form'),
            new $model(),
            $this->getOption($key, 'form_options')
        );
    }

    public function getTemplate($key, $templateKey)
    {
        $templateConfiguration = $this->getOption($key, 'template');
        return $this->templateResolver->resolve($templateConfiguration[$templateKey]);
    }
}
