<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RevisionBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\RevisionBundle\Entity\AbstractRevisionAware;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class RevisionAwareColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        $value = null;
        if ($resource instanceof AbstractRevisionAware) {
            $metadata = $this->resourceManager->getMetadata($resource->getResourceAlias());
            $value = $metadata ? $this->translator->trans($metadata->getLabel(), [], $metadata->getTranslationDomain()) : null;
        }

        $data->set('value', $value);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'revision_aware.label.type',
            'translation_domain' => 'EnhavoRevisionBundle',
            'component' => 'column-text',
            'model' => 'TextColumn',
        ]);
    }
}
