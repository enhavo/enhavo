<?php

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Enhavo\Bundle\AppBundle\Routing\Routeable;
use Enhavo\Bundle\AppBundle\Routing\Slugable;
use Enhavo\Bundle\TranslationBundle\Route\RouteGuesser;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;
use Enhavo\Bundle\AppBundle\Slugifier\Slugifier;
use Enhavo\Bundle\AppBundle\Entity\Route;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class SaveTranslatorListener
 *
 * This Listener just trigger the postFlush function of the translator
 *
 * @package Enhavo\Bundle\TranslationBundle\EventListener
 */
class SaveTranslatorListener
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * DoctrineSubscriber constructor.
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function postSave()
    {
        $this->translator->postFlush();
    }
}