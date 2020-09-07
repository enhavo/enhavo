<?php
/**
 * DoubleOptInStrategyType.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;

use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Strategy\AbstractStrategyType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class DoubleOptInStrategyType extends AbstractStrategyType
{
    /** @var RepositoryInterface */
    private $bufferRepository;

    /** @var RouterInterface */
    private $router;

    /**
     * DoubleOptInStrategyType constructor.
     * @param RepositoryInterface $bufferRepository
     * @param RouterInterface $router
     */
    public function __construct(RepositoryInterface $bufferRepository, RouterInterface $router)
    {
        $this->bufferRepository = $bufferRepository;
        $this->router = $router;
    }

    public function addSubscriber(SubscriberInterface $subscriber, array $options = [])
    {
        $subscriber->setCreatedAt(new \DateTime());
        $this->setToken($subscriber);
        $this->getStorage()->saveSubscriber($subscriber, 'local');
        $this->notifySubscriber($subscriber, $subscriber->getType());
        return 'subscriber.form.message.double_opt_in';
    }

    public function activateSubscriber(SubscriberInterface $subscriber, $type)
    {
        $this->bufferRepository->delete($subscriber);
        $this->getStorage()->saveSubscriber($subscriber);
        $this->notifyAdmin($subscriber, $type);
        $this->confirmSubscriber($subscriber, $subscriber->getType());
    }

    private function notifySubscriber(SubscriberInterface $subscriber, $type)
    {
        $notify = $this->getTypeOption('notify', $type, true);
        if ($notify) {
            $link = $this->router->generate('enhavo_newsletter_subscribe_activate', [
                'token' => $subscriber->getToken(),
                'type' => $subscriber->getType()
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $template = $this->getTypeOption('template', $type, 'EnhavoNewsletterBundle:Subscriber:Email/double-opt-in.html.twig');
            $from = $this->getTypeOption('from', $type, 'no-reply@enhavo.com');
            $senderName = $this->getTypeOption('sender_name', $type, 'enhavo');

            $message = new \Swift_Message();
            $message->setSubject($this->getSubject())
                ->setFrom($from, $senderName)
                ->setTo($subscriber->getEmail())
                ->setBody($this->renderTemplate($template, [
                    'subscriber' => $subscriber,
                    'link' => $link
                ]), 'text/html');

            $this->sendMessage($message);
        }
    }

    private function confirmSubscriber(SubscriberInterface $subscriber, $type)
    {
        $notify = $this->getTypeOption('confirm', $type, true);
        if ($notify) {
            // TODO add unsubscribe/change subscription link
            $template = $this->getTypeOption('confirmation_template', $type, 'EnhavoNewsletterBundle:Subscriber:Email/confirmation.html.twig');
            $from = $this->getTypeOption('from', $type, 'no-reply@enhavo.com');
            $senderName = $this->getTypeOption('sender_name', $type, 'enhavo');

            $message = new \Swift_Message();
            $message->setSubject($this->getSubject())
                ->setFrom($from, $senderName)
                ->setTo($subscriber->getEmail())
                ->setBody($this->renderTemplate($template, [
                    'subscriber' => $subscriber
                ]), 'text/html');

            $this->sendMessage($message);
        }
    }

    private function notifyAdmin(SubscriberInterface $subscriber, $type)
    {
        $notify = $this->getTypeOption('admin_notify', $type, false);
        if ($notify) {
            $template = $this->getTypeOption('admin_template', $type, 'EnhavoNewsletterBundle:Subscriber:Email/notify-admin.html.twig');
            $from = $this->getTypeOption('from', $$type, 'no-reply@enhavo.com');
            $senderName = $this->getTypeOption('sender_name', $type, 'enhavo');
            $to = $this->getTypeOption('admin_email', $type, 'no-reply@enhavo.com');

            $message = new \Swift_Message();
            $message->setSubject($this->getAdminSubject($type))
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

    public function exists(SubscriberInterface $subscriber, array $options = []): bool
    {
        $checkExists = $this->getTypeOption('check_exists', $subscriber->getType(), false);

        if ($checkExists) {
            if ($this->repository->find($subscriber)) {
                return true;
            }

            if ($this->getStorage()->exists($subscriber)) {
                return true;
            }
        }

        return false;
    }

    public function handleExists(SubscriberInterface $subscriber, array $options = [])
    {
        $subscriber = $this->repository->findOneByEmail($subscriber->getEmail());

        if (!$subscriber->isActive()) {
            $this->setToken($subscriber);
            $this->notifySubscriber($subscriber, $subscriber->getType());

            return 'subscriber.form.error.sent_again';
        }
        return 'subscriber.form.error.exists';
    }

    public function getActivationTemplate($type)
    {
        return $this->getTypeOption('activation_template', $type, 'EnhavoNewsletterBundle:Subscriber:activate.html.twig');
    }

    public static function getName(): ?string
    {
        return 'double_opt_in';
    }
}
