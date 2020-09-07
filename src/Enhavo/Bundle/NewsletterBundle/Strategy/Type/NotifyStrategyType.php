<?php

namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Strategy\AbstractStrategyType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotifyStrategyType extends AbstractStrategyType
{
    /** @var NewsletterManager */
    private $newsletterManager;

    /** @var RepositoryInterface */
    private $bufferRepository;

    /**
     * NotifyStrategyType constructor.
     * @param NewsletterManager $newsletterManager
     * @param RepositoryInterface $bufferRepository
     */
    public function __construct(NewsletterManager $newsletterManager, RepositoryInterface $bufferRepository)
    {
        $this->newsletterManager = $newsletterManager;
        $this->bufferRepository = $bufferRepository;
    }


    public function addSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $subscriber->setCreatedAt(new \DateTime());
        $subscriber->setActivatedAt(new \DateTime());
        $subscriber->setActive(true);
        $this->getStorage()->saveSubscriber($subscriber);
        $this->notifySubscriber($subscriber, $options);
        $this->notifyAdmin($subscriber, $options);

        return 'subscriber.form.message.notify';
    }

    private function notifySubscriber(SubscriberInterface $subscriber, array $options)
    {
        if ($options['notify']) {
            $template = $options['template'];
            $from = $options['from'];
            $senderName = $options['sender_name'];
            $subject = '';//$this->getSubject()

            $message = $this->newsletterManager->createMessage($from, $senderName, $subscriber->getEmail(), $subject, $template, [
                'subscriber' => $subscriber
            ]);
            $this->newsletterManager->sendMessage($message);
        }
    }

    private function notifyAdmin(SubscriberInterface $subscriber, array $options)
    {
        if ($options['notify_admin']) {
            $template = $options['admin_template'];
            $from = $options['from'];
            $senderName = $options['sender_name'];
            $to = $options['admin_email'];
            $subject = $this->getAdminSubject($options);

            $message = $this->newsletterManager->createMessage($from, $senderName, $to, $subject, $template, [
                'subscriber' => $subscriber
            ]);
            $this->newsletterManager->sendMessage($message);
        }
    }

    private function getAdminSubject(array $options)
    {
        $subject = $options['admin_subject'];
        $translationDomain = $options['translation_domain'];

        return '';//$this->translator->trans($subject, [], $translationDomain);
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        $checkExists = $options['check_exists'];

        if ($checkExists) {
            return $this->getStorage()->exists($subscriber);
        }
        return false;
    }

    public function handleExists(SubscriberInterface $subscriber, array $options)
    {
        return 'subscriber.form.error.exists';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'notify_admin' => true,
            'admin_template' => 'EnhavoNewsletterBundle:Subscriber:Email/notify-admin.html.twig',
            'sender_name' => 'enhavo',
            'template' => 'EnhavoNewsletterBundle:Subscriber:Email/notify.html.twig',
            'admin_subject' => 'subscriber.mail.admin.subject',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ]);
    }

    public static function getName(): ?string
    {
        return 'notify';
    }

}
