<?php
/**
 * AbstractStrategy.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Subscriber\SubscriberManager;

abstract class AbstractStrategy extends AbstractType implements StrategyInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $typeOptions;

    public function __construct($options, $typeOptions = [])
    {
        $this->options = $options;
        $this->typeOptions = $typeOptions;
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

    protected function setToken(SubscriberInterface $subscriber)
    {
        $token = $this->container->get('fos_user.util.token_generator')->generateToken();
        $subscriber->setToken($token);
    }

    private function getTypeOptions($type)
    {
        $options = $this->options;
        if(isset($this->typeOptions[$type])) {
            $options = array_merge_recursive($options, $this->typeOptions[$type]);
        }
        return $options;
    }

    protected function getTypeOption($key, $type, $default = null)
    {
        return $this->getOption($key, $this->getTypeOptions($type), $default);
    }
}