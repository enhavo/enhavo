<?php

namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Strategy\AbstractStrategyType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class AcceptStrategyType extends AbstractStrategyType
{
    /** @var NewsletterManager */
    private $newsletterManager;

    /** @var RepositoryInterface */
    private $bufferRepository;

    /** @var RouterInterface */
    private $router;

    /**
     * AcceptStrategyType constructor.
     * @param NewsletterManager $newsletterManager
     * @param RepositoryInterface $bufferRepository
     * @param RouterInterface $router
     */
    public function __construct(NewsletterManager $newsletterManager, RepositoryInterface $bufferRepository, RouterInterface $router)
    {
        $this->newsletterManager = $newsletterManager;
        $this->bufferRepository = $bufferRepository;
        $this->router = $router;
    }

    public function addSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->setToken($subscriber);
        $this->getStorage()->saveSubscriber($subscriber);
        $this->notifyAdmin($subscriber, $options);

        return 'subscriber.form.message.accept';
    }

    public function activateSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->bufferRepository->remove($subscriber);
        $this->getStorage()->saveSubscriber($subscriber);
        $this->notifySubscriber($subscriber, $options);
    }

    private function notifySubscriber(SubscriberInterface $subscriber, array $options)
    {
        if ($options['notify']) {
            $template = $options['template'];
            $from = $options['from'];
            $senderName = $options['sender_name'];
            $subject = '';//$this->getSubject();

            $message = $this->newsletterManager->createMessage($from, $senderName, $subscriber->getEmail(), $subject, $template, [
                'subscriber' => $subscriber
            ]);
            $this->newsletterManager->sendMessage($message);

        }
    }

    private function notifyAdmin(SubscriberInterface $subscriber, array $options)
    {
        $link = $this->router->generate($options['accept_route'], [
            'token' => $subscriber->getToken(),
            'type' => $subscriber->getType()
        ], true);

        $template = $options['admin_template'];
        $from = $options['from'];
        $senderName = $options['sender_name'];
        $to = $options['admin_email'];
        $subject = $this->getAdminSubject($options);

        $message = $this->newsletterManager->createMessage($from, $senderName, $to, $subject, $template, [
            'subscriber' => $subscriber,
            'link' => $link
        ]);
        $this->newsletterManager->sendMessage($message);
    }

    private function getAdminSubject(array $options)
    {
        $subject = $options['admin_subject'];
        $translationDomain = $options['translation_domain'];

        return '';//$this->translator->trans($subject, [], $translationDomain);
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
        return 'subscriber.form.error.exists';
    }

    public function getActivationTemplate(array $options)
    {
        return $options['activation_template'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'activation_template' => 'EnhavoNewsletterBundle:Subscriber:accept.html.twig',
            'admin_subject' => 'newsletter.subscribtion',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'admin_template' => 'EnhavoNewsletterBundle:Subscriber:Email/accept-admin.html.twig',
            'template' => 'EnhavoNewsletterBundle:Subscriber:Email/accept.html.twig',
            'accept_route' => 'enhavo_newsletter_subscribe_accept'
        ]);
    }

    public static function getName(): ?string
    {
        return 'accept';
    }

}
