<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Tab;

use Enhavo\Bundle\ResourceBundle\Tab\AbstractTabType;
use Enhavo\Bundle\ResourceBundle\Tab\Type\FormTabType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaTabType extends AbstractTabType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'content.label.meta',
            'translation_domain' => 'EnhavoContentBundle',
            'arrangement' => [
                'page_title',
                'slug',
                'route.path',
                'meta_description',
                'canonicalUrl',
                'noIndex',
                'noFollow',
                'openGraphTitle',
                'openGraphDescription',
                'openGraphImage',
            ],
        ]);
    }

    public static function getParentType(): ?string
    {
        return FormTabType::class;
    }

    public static function getName(): ?string
    {
        return 'meta';
    }
}
