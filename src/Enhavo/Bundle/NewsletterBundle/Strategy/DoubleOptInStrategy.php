<?php
/**
 * DoubleOptInStrategy.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\LocalStorage;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageResolver;

class DoubleOptInStrategy extends AbstractStrategy
{
    /**
     * @var LocalStorage
     */
    private $localStorage;

    /**
     * @var StorageResolver
     */
    private $storageResolver;

    /**
     * @var Resolver
     */
    private $formResolver;

    public function __construct($options, LocalStorage $localStorage, $storageResolver, $formResolver)
    {
        parent::__construct($options);
        $this->localStorage = $localStorage;
        $this->storageResolver = $storageResolver;
        $this->formResolver = $formResolver;
    }

    public function addSubscriber(SubscriberInterface $subscriber, $type = null)
    {
        $subscriber->setCreatedAt(new \DateTime());
        $subscriber->setActive(false);
        $this->setToken($subscriber);
        $this->localStorage->saveSubscriber($subscriber);
        $this->notifySubscriber($subscriber, $type);
        return 'subscriber.form.message.double_opt_in';
    }

    public function activateSubscriber(SubscriberInterface $subscriber, $type)
    {
        $subscriber->setActive(true);
        $subscriber->setToken(null);
        $this->localStorage->saveSubscriber($subscriber);
        $this->getSubscriberManager()->saveSubscriber($subscriber, $type);
        $this->notifyAdmin($subscriber);
    }

    private function notifySubscriber(SubscriberInterface $subscriber, $type)
    {
        $link = $this->getRouter()->generate('enhavo_newsletter_subscribe_activate', [
            'token' => $subscriber->getToken(),
            'type' => $type
            ], true);
        $template = $this->getOption('template', $this->options, 'EnhavoNewsletterBundle:Subscriber:Email/double-opt-in.html.twig');
        $message = \Swift_Message::newInstance()
            ->setSubject($this->getSubject())
            ->setFrom($this->getOption('from', $this->options, 'no-reply@enhavo.com'))
            ->setTo($subscriber->getEmail())
            ->setBody($this->renderTemplate($template, [
                'subscriber' => $subscriber,
                'link' => $link
            ]), 'text/html');
        $this->sendMessage($message);
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
        $storage = $this->storageResolver->resolve($type);
        $groupNames = $this->formResolver->resolveGroupNames($type, $subscriber);
        if($storage instanceof LocalStorage){
            return $this->localStorage->exists($subscriber, $groupNames);
        }
        return $this->localStorage->exists($subscriber, $groupNames) || $storage->exists($subscriber, $groupNames);
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        $subscriber = $this->localStorage->getSubscriber($subscriber->getEmail());
        if(!$subscriber->isActive()) {
            $this->setToken($subscriber);
            $this->notifySubscriber($subscriber);
            return 'subscriber.form.error.sent_again';
        }
        return 'subscriber.form.error.exists';
    }

    public function getActivationTemplate()
    {
        return $this->getOption('activation_template', $this->options, 'EnhavoNewsletterBundle:Subscriber:activate.html.twig');
    }

    private function getRouter()
    {
        return $this->container->get('router');
    }

    public function getType()
    {
        return 'double_opt_in';
    }
}