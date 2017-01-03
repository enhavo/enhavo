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

    public function __construct($options, $storageResolver, $formResolver)
    {
        parent::__construct($options);
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
        if($this->getOption('notify', $this->options, true)) {
            $template = $this->getOption('template', $this->options, 'EnhavoNewsletterBundle:Subscriber:Email/notify.html.twig');
            $message = \Swift_Message::newInstance()
                ->setSubject($this->getSubject())
                ->setFrom($this->getOption('from', $this->options, 'no-reply@enhavo.com'))
                ->setTo($subscriber->getEmail())
                ->setBody($this->renderTemplate($template, [
                    'subscriber' => $subscriber
                ]), 'text/html');
            $this->sendMessage($message);
        }
    }

    private function notifyAdmin(SubscriberInterface $subscriber)
    {
        if($this->getOption('admin_notify', $this->options, false)) {
            $template = $this->getOption('admin_template', $this->options, 'EnhavoNewsletterBundle:Subscriber:Email/notify-admin.html.twig');
            $message = \Swift_Message::newInstance()
                ->setSubject($this->getAdminSubject())
                ->setFrom($this->getOption('from', $this->options, 'no-reply@enhavo.com'))
                ->setTo($this->getOption('admin_email', $this->options, 'no-reply@enhavo.com'))
                ->setBody($this->renderTemplate($template, [
                    'subscriber' => $subscriber
                ]), 'text/html');
            $this->sendMessage($message);
        }
    }

    private function getAdminSubject()
    {
        $subject = $this->getOption('admin_subject', $this->options, 'Newsletter Subscription');
        $translationDomain = $this->getOption('admin_translation_domain', $this->options, null);
        return $this->container->get('translator')->trans($subject, [], $translationDomain);
    }

    public function exists(SubscriberInterface $subscriber, $type)
    {
        /** @var StorageInterface $storage */
        $storage = $this->storageResolver->resolve($type);
        $groupNames = $this->formResolver->resolveGroupNames($type, $subscriber);
        return $storage->exists($subscriber, $groupNames);
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