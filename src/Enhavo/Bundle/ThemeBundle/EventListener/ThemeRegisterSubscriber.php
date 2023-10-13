<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-08
 * Time: 19:44
 */

namespace Enhavo\Bundle\ThemeBundle\EventListener;

use Doctrine\DBAL\Exception;
use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\ThemeBundle\Theme\ThemeManager;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ThemeRegisterSubscriber implements EventSubscriberInterface
{
    /** @var TemplateResolver */
    private $templateResolver;

    /** @var ThemeManager */
    private $themeManager;

    /**
     * ThemeListener constructor.
     * @param ThemeManager $themeManager
     * @param TemplateResolver $templateResolver
     */
    public function __construct(ThemeManager $themeManager, TemplateResolver $templateResolver)
    {
        $this->themeManager = $themeManager;
        $this->templateResolver = $templateResolver;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::REQUEST => [
                ['onRequest', 10]
            ],
            ConsoleEvents::COMMAND => [
                ['onConsoleCommand', 10]
            ]
        ];
    }

    /**
     * @param RequestEvent $event
     */
    public function onRequest(RequestEvent $event)
    {
        $path = $this->themeManager->getTheme()->getTemplate()->getPath();
        if($path !== null) {
            $this->templateResolver->registerPath($path, 200);
        }
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        try {
            $path = $this->themeManager->getTheme()->getTemplate()->getPath();
            if($path !== null) {
                $this->templateResolver->registerPath($path, 200);
            }
        } catch (Exception $e) {
            // Don't throw exception here, because if the schema is not loaded or incorrect, the console should not
            // be affected, to give the user the option the fix his schema with the doctrine console commands
        }
    }
}
