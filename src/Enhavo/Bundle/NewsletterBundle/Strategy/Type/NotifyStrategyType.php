<?php

namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Strategy\AbstractStrategyType;
use Enhavo\Bundle\NewsletterBundle\Strategy\MailSubjectTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotifyStrategyType extends AbstractStrategyType
{
    use MailSubjectTrait;

    /** @var NewsletterManager */
    private $newsletterManager;

    /**
     * NotifyStrategyType constructor.
     * @param NewsletterManager $newsletterManager
     */
    public function __construct(NewsletterManager $newsletterManager)
    {
        $this->newsletterManager = $newsletterManager;
    }


    public function addSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->preAddSubscriber($subscriber);

        $subscriber->setCreatedAt(new \DateTime());
        $this->getStorage()->saveSubscriber($subscriber);
        $this->notifySubscriber($subscriber, $options);
        $this->notifyAdmin($subscriber, $options);

        $this->postAddSubscriber($subscriber);

        return 'subscriber.form.message.notify';
    }

    private function notifySubscriber(SubscriberInterface $subscriber, array $options)
    {
        if ($options['notify']) {
            $template = $options['template'];
            $from = $options['from'];
            $senderName = $options['sender_name'];
            $subject = $this->getSubject($options);

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

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        if ($options['check_exists']) {
            return $this->getStorage()->exists($subscriber);
        }
        return false;
    }

    public function handleExists(SubscriberInterface $subscriber, array $options)
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'notify_admin' => true,
            'admin_template' => 'EnhavoNewsletterBundle:mail/subscriber:notify-admin.html.twig',
            'sender_name' => 'enhavo',
            'template' => 'EnhavoNewsletterBundle:mail/subscriber:notify.html.twig',
        ]);
    }

    public static function getName(): ?string
    {
        return 'notify';
    }

}
