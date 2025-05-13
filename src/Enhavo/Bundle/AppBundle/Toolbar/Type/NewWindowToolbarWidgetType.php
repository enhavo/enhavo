<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Toolbar\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Toolbar\AbstractToolbarWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewWindowToolbarWidgetType extends AbstractToolbarWidgetType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function createViewData(array $options, Data $data): void
    {
        $data['label'] = $this->getLabel($options);
        $data['icon'] = $options['icon'];
    }

    private function getLabel($config): string
    {
        return $this->translator->trans($config['label'], [], $config['translation_domain'] ?: null);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'open_in_new',
            'component' => 'toolbar-widget-new-window',
            'translation_domain' => null,
            'label' => null,
            'model' => 'NewWindowToolbarWidget',
        ]);
    }

    public static function getName(): ?string
    {
        return 'new_window';
    }
}
