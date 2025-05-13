<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Twig;

use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SubscribeFormRenderer extends AbstractExtension
{
    use ContainerAwareTrait;

    /** @var SubscriptionManager */
    private $subscriptionManager;

    /** @var Environment */
    private $twig;

    /**
     * SubscribeFormRenderer constructor.
     */
    public function __construct(SubscriptionManager $subscriptionManager, Environment $twig)
    {
        $this->subscriptionManager = $subscriptionManager;
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('subscribe_form', [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param null $subscriptionName
     * @param null $template
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @return string
     */
    public function render($subscriptionName = null, $template = null)
    {
        $subscription = $this->subscriptionManager->getSubscription($subscriptionName);
        $formConfig = $subscription->getFormConfig();
        $form = $this->subscriptionManager->createForm($subscription, null);

        $formTemplate = $template ?? $formConfig['template'];

        return $this->twig->render($formTemplate, [
            'form' => $form->createView(),
            'subscription' => $subscription,
        ]);
    }

    public function getName()
    {
        return 'subscribe_form';
    }
}
