<?php
/**
 * PreviewListener.php
 *
 * @since 26/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\EventListener;


use Enhavo\Bundle\AppBundle\Event\PreviewEvent;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleResolver;

class PreviewListener
{
    /**
     * @var LocaleResolver
     */
    protected $localeResolver;

    public function __construct(LocaleResolver $localeResolver)
    {
        $this->localeResolver = $localeResolver;
    }

    public function onInitPreview(PreviewEvent $event)
    {
        $request = $event->getRequest();
        $locale = $request->get('locale');
        $this->localeResolver->setLocale($locale);
    }
}
