<?php
/**
 * AbstractStrategy.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
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

    /**
     * @var TokenGeneratorInterface
     */
    protected $tokenGenerator;

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

    /**
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function setTokenGenerator(TokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param SubscriberInterface $subscriber
     */
    protected function setToken(SubscriberInterface $subscriber)
    {
        $token = $this->tokenGenerator->generateToken();
        $subscriber->setToken($token);
    }

    private function getTypeOptions($type)
    {
        $options = $this->options;
        if(isset($this->typeOptions[$type]) && isset($this->typeOptions[$type]['strategy'])) {
            $options = $this->mergeArraysRecursive($options, $this->typeOptions[$type]['strategy']);
        }
        return $options;
    }

    /**
     * @param array $array1
     * @param array $array2
     * @return array
     */
    private function mergeArraysRecursive($array1, $array2)
    {
        $result = array();
        foreach($array1 as $key => $value) {
            if (isset($array2[$key])) {
                if (is_array($value)) {
                    if (is_array($array2[$key])) {
                        $result[$key] = $this->mergeArraysRecursive($value, $array2[$key]);
                    } else {
                        $result[$key] = $array2[$key];
                    }
                } else {
                    $result[$key] = $array2[$key];
                }
            } else {
                $result[$key] = $value;
            }
        }
        foreach($array2 as $key => $value) {
            if (!isset($result[$key])) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    protected function getTypeOption($key, $type, $default = null)
    {
        return $this->getOption($key, $this->getTypeOptions($type), $default);
    }
}
