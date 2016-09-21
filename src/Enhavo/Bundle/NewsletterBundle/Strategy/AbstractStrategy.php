<?php
/**
 * AbstractStrategy.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\NewsletterBundle\Subscriber\SubscriberManager;

abstract class AbstractStrategy extends AbstractType implements StrategyInterface
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * @return SubscriberManager
     */
    public function getSubscriberManager()
    {
        return $this->container->get('enhavo_newsletter.subscriber.manager');
    }

    public function sendMessage($message)
    {
        return $this->container->get('mailer')->send($message);
    }

    public function getSubject()
    {
        $subject = $this->getOption('subject', $this->options, 'Newsletter Subscription');
        $translationDomain = $this->getOption('translation_domain', $this->options, null);
        return $this->container->get('translator')->trans($subject, [], $translationDomain);
    }
}