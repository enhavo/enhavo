<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-08
 * Time: 19:44
 */

namespace Enhavo\Bundle\ThemeBundle\EventListener;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\ThemeBundle\Theme\ThemeManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ThemeRegisterSubscriber implements EventSubscriberInterface
{
    /**
     * @var TemplateManager
     */
    private $templateManager;

    /**
     * @var ThemeManager
     */
    private $themeManager;

    /**
     * ThemeListener constructor.
     * @param ThemeManager $themeManager
     * @param TemplateManager $templateManager
     */
    public function __construct(ThemeManager $themeManager, TemplateManager $templateManager)
    {
        $this->themeManager = $themeManager;
        $this->templateManager = $templateManager;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::REQUEST => [
                ['onRequest', 10]
            ],
        ];
    }

    /**
     * @param RequestEvent $event
     */
    public function onRequest(RequestEvent $event)
    {
        $path = $this->themeManager->getTheme()->getTemplate()->getPath();
        if($path !== null) {
            $this->templateManager->registerPath($path, TemplateManager::PRIORITY_HIGH);
        }
    }
}
