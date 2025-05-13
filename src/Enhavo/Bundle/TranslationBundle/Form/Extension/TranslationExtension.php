<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Extension;

use Enhavo\Bundle\TranslationBundle\EventListener\AccessControlInterface;
use Enhavo\Bundle\TranslationBundle\Form\EventListener\ReplaceTranslationTypeListener;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

class TranslationExtension extends AbstractTypeExtension
{
    public function __construct(
        private TranslationManager $translationManager,
        private AccessControlInterface $accessControl,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$this->isTranslatable($options)) {
            return;
        }

        $builder->addEventSubscriber(new ReplaceTranslationTypeListener($this->translationManager));
    }

    private function isTranslatable($options)
    {
        return $options['compound']
            && $this->translationManager->isEnabled()
            && $this->accessControl->isAccess();
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
