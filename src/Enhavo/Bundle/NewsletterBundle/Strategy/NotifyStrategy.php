<?php
/**
 * NotifyStrategy.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;


use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageResolver;

class NotifyStrategy extends AbstractStrategy
{
    /**
     * @var StorageResolver
     */
    private $storageResolver;

    /**
     * @var Resolver
     */
    private $formResolver;

    /**
     * NotifyStrategy constructor.
     *
     * @param array $options
     * @param array $typeOptions
     * @param StorageResolver $storageResolver
     * @param Resolver $formResolver
     */
    public function __construct($options, $typeOptions, $storageResolver, $formResolver)
    {
        parent::__construct($options, $typeOptions);
        $this->storageResolver = $storageResolver;
        $this->formResolver = $formResolver;
    }

    public function addSubscriber(SubscriberInterface $subscriber, $type = null)
    {
        $subscriber->setCreatedAt(new \DateTime());
        $subscriber->setActivatedAt(new \DateTime());
        $subscriber->setActive(true);
        $this->getSubscriberManager()->saveSubscriber($subscriber, $type);
        $this->notifySubscriber($subscriber);
        $this->notifyAdmin($subscriber);
        return 'subscriber.form.message.notify';
    }

    private function notifySubscriber(SubscriberInterface $subscriber)
    {
        $notify = $this->getTypeOption('notify', $subscriber->getType(), true);

        if($notify) {
            $template = $this->getTypeOption('template', $subscriber->getType(), 'EnhavoNewsletterBundle:Subscriber:Email/notify.html.twig');
            $from = $this->getTypeOption('from', $subscriber->getType(), 'no-reply@enhavo.com');
            $senderName = $this->getTypeOption('sender_name', $subscriber->getType(), 'enahvo');

            $message = \Swift_Message::newInstance()
                ->setSubject($this->getSubject())
                ->setFrom($from, $senderName)
                ->setTo($subscriber->getEmail())
                ->setBody($this->renderTemplate($template, [
                    'subscriber' => $subscriber
                ]), 'text/html');
            $this->sendMessage($message);
        }
    }

    private function notifyAdmin(SubscriberInterface $subscriber)
    {
        $notify = $this->getTypeOption('admin_notify', $subscriber->getType(), false);

        if($notify) {
            $template = $this->getTypeOption('admin_template', $subscriber->getType(), 'EnhavoNewsletterBundle:Subscriber:Email/notify-admin.html.twig');
            $from = $this->getTypeOption('from', $subscriber->getType(), 'no-reply@enhavo.com');
            $senderName = $this->getTypeOption('sender_name', $subscriber->getType(), 'enahvo');
            $to = $this->getTypeOption('admin_email', $subscriber->getType(), 'no-reply@enhavo.com');

            $message = \Swift_Message::newInstance()
                ->setSubject($this->getAdminSubject($subscriber->getType()))
                ->setFrom($from, $senderName)
                ->setTo($to)
                ->setBody($this->renderTemplate($template, [
                    'subscriber' => $subscriber
                ]), 'text/html');
            $this->sendMessage($message);
        }
    }

    private function getAdminSubject($type)
    {
        $subject = $this->getTypeOption('admin_subject', $type, 'Newsletter Subscription');
        $translationDomain = $this->getTypeOption('admin_translation_domain', $type, null);

        return $this->container->get('translator')->trans($subject, [], $translationDomain);
    }

    public function exists(SubscriberInterface $subscriber)
    {
        $checkExists = $this->getTypeOption('check_exists',$subscriber->getType(), false);

        if($checkExists) {
            /** @var StorageInterface $storage */
            $storage = $this->storageResolver->resolve($subscriber->getType());
            return $storage->exists($subscriber);
        }
        return false;
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        return 'subscriber.form.error.exists';
    }

    public function getType()
    {
        return 'notify';
    }
}