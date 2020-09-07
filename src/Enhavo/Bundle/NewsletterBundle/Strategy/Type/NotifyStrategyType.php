<?php
/**
 * NotifyStrategyType.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;

use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Strategy\AbstractStrategyType;
use Enhavo\Bundle\NewsletterBundle\Subscribtion\SubscribtionManager;
use Twig\Environment;

class NotifyStrategyType extends AbstractStrategyType
{

    public function addSubscriber(SubscriberInterface $subscriber, $type = null)
    {
        $subscriber->setCreatedAt(new \DateTime());
        $subscriber->setActivatedAt(new \DateTime());
        $subscriber->setActive(true);
        $this->storage->saveSubscriber($subscriber, $type);
        $this->notifySubscriber($subscriber);
        $this->notifyAdmin($subscriber);

        return 'subscriber.form.message.notify';
    }

    private function notifySubscriber(SubscriberInterface $subscriber)
    {
        $notify = $this->getTypeOption('notify', $subscriber->getType(), true);

        if ($notify) {
            $template = $this->getTypeOption('template', $subscriber->getType(), 'EnhavoNewsletterBundle:Subscriber:Email/notify.html.twig');
            $from = $this->getTypeOption('from', $subscriber->getType(), 'no-reply@enhavo.com');
            $senderName = $this->getTypeOption('sender_name', $subscriber->getType(), 'enhavo');

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

    private function notifyAdmin(SubscriberInterface $subscriber)
    {
        $notify = $this->getTypeOption('admin_notify', $subscriber->getType(), false);

        if ($notify) {
            $template = $this->getTypeOption('admin_template', $subscriber->getType(), 'EnhavoNewsletterBundle:Subscriber:Email/notify-admin.html.twig');
            $from = $this->getTypeOption('from', $subscriber->getType(), 'no-reply@enhavo.com');
            $senderName = $this->getTypeOption('sender_name', $subscriber->getType(), 'enhavo');
            $to = $this->getTypeOption('admin_email', $subscriber->getType(), 'no-reply@enhavo.com');

            $message = new \Swift_Message();
            $message->setSubject($this->getAdminSubject($subscriber->getType()))
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
            return $this->subscriberManager->exists($subscriber, $subscriber->getType());
        }
        return false;
    }

    public function handleExists(SubscriberInterface $subscriber, array $options = [])
    {
        return 'subscriber.form.error.exists';
    }

    public static function getName(): ?string
    {
        return 'notify';
    }

}
