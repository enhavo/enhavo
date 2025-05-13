<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
