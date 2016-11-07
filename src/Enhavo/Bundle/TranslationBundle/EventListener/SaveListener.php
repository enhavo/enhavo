<?php

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Enhavo\Bundle\TranslationBundle\Translator\Translator;

/*
 * Tells the IndexEngine to index a resource
 */
class SaveListener
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
        $this->translator->updateReferences();
    }
}