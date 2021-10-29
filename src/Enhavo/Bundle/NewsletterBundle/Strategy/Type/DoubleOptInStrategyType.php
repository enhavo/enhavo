<?php
/**
 * DoubleOptInStrategyType.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Strategy\AbstractStrategyType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class DoubleOptInStrategyType extends AbstractStrategyType
{
    /** @var NewsletterManager */
    private $newsletterManager;

    /** @var PendingSubscriberManager */
    private $pendingManager;

    /** @var RouterInterface */
    private $router;

    /**
     * DoubleOptInStrategyType constructor.
     * @param NewsletterManager $newsletterManager
     * @param PendingSubscriberManager $pendingManager
     * @param RouterInterface $router
     */
    public function __construct(NewsletterManager $newsletterManager, PendingSubscriberManager $pendingManager, RouterInterface $router)
    {
        $this->newsletterManager = $newsletterManager;
        $this->pendingManager = $pendingManager;
        $this->router = $router;
    }

    public function addSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->preAddSubscriber($subscriber);
        $subscriber->setCreatedAt(new \DateTime());
        $pending = $this->pendingManager->createFrom($subscriber);

        $this->pendingManager->save($pending);
        $subscriber->setConfirmationToken($pending->getConfirmationToken());
        $this->notifySubscriber($subscriber, $options);

        $this->postAddSubscriber($subscriber);

        return 'subscriber.form.message.double_opt_in';
    }

    public function activateSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->preActivateSubscriber($subscriber);
        $this->getStorage()->saveSubscriber($subscriber);
        $this->pendingManager->removeBy($subscriber->getEmail(), $subscriber->getSubscription());

        $this->notifyAdmin($subscriber, $options);
        $this->confirmSubscriber($subscriber, $options);

        $this->postActivateSubscriber($subscriber);
    }

    private function notifySubscriber(SubscriberInterface $subscriber, array $options)
    {
        if ($options['notify']) {
            $link = $this->router->generate($options['activate_route'], array_merge($options['activate_route_parameters'], [
                'token' => $subscriber->getConfirmationToken(),
                'type' => $subscriber->getSubscription()
            ]), UrlGeneratorInterface::ABSOLUTE_URL);

            $template = $options['template'];
            $from = $options['from'];
            $senderName = $options['sender_name'];
            $subject = $this->trans($options['subject'], [], $options['translation_domain']);

            $message = $this->newsletterManager->createMessage($from, $senderName, $subscriber->getEmail(), $subject, $template, [
                'subscriber' => $subscriber,
                'link' => $link
            ], $options['content_type']);
            $this->newsletterManager->sendMessage($message);
        }
    }

    private function confirmSubscriber(SubscriberInterface $subscriber, array $options)
    {
        if ($options['confirm']) {
            $template = $options['confirmation_template'];
            $from = $options['from'];
            $senderName = $options['sender_name'];
            $subject = $this->trans($options['confirmation_subject'], [], $options['translation_domain']);
            $unsubscribeLink = $this->router->generate('enhavo_newsletter_subscriber_unsubscribe', [ // add option to set unsubscribe_route
                'token' => urlencode($subscriber->getConfirmationToken()),
                'type' => $subscriber->getSubscription(),
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $message = $this->newsletterManager->createMessage($from, $senderName, $subscriber->getEmail(), $subject, $template, [
                'subscriber' => $subscriber,
                'unsubscribe_link' => $unsubscribeLink,
            ], $options['content_type']);

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
            $subject = $this->trans($options['admin_subject'], [], $options['translation_domain']);

            $message = $this->newsletterManager->createMessage($from, $senderName, $to, $subject, $template, [
                'subscriber' => $subscriber
            ], $options['content_type']);
            $this->newsletterManager->sendMessage($message);
        }
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        if ($options['check_exists']) {
            if ($this->pendingManager->findOneBy($subscriber->getEmail(), $subscriber->getSubscription())) {
                return true;
            }

            if ($this->getStorage()->exists($subscriber)) {
                return true;
            }
        }

        return false;
    }

    public function handleExists(SubscriberInterface $subscriber, array $options)
    {
        $pending = $this->pendingManager->findOneBy($subscriber->getEmail(), $subscriber->getSubscription());

        if ($pending) {
            $this->pendingManager->save($pending); // to set a new confirmation token
            $subscriber->setConfirmationToken($pending->getConfirmationToken());
            $this->notifySubscriber($subscriber, $options);

            return 'subscriber.form.error.sent_again';
        }

        return null;
    }

    public function getActivationTemplate(array $options): ?string
    {
        return $options['activation_template'];
    }

    public function getUnsubscribeTemplate(array $options): ?string
    {
        return $options['unsubscribe_template'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'notify_admin' => false,
            'activation_template' => 'theme/resource/subscriber/activate.html.twig',
            'unsubscribe_template' => 'theme/resource/subscriber/unsubscribe.html.twig',
            'template' => 'mail/subscriber/double-opt-in.html.twig',
            'confirmation_template' => 'mail/subscriber/confirmation.html.twig',
            'confirmation_subject' => 'subscriber.mail.confirm.subject',
            'admin_template' => 'mail/subscriber/notify-admin.html.twig',
            'activate_route' => 'enhavo_newsletter_subscribe_activate',
            'activate_route_parameters' => [],
            'confirm' => true,
        ]);
    }

    public static function getName(): ?string
    {
        return 'double_opt_in';
    }
}
