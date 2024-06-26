<?php

namespace Enhavo\Bundle\ResourceBundle\Batch\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseBatchType extends AbstractBatchType
{
    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function createViewData(array $options, Data $data): void
    {
        $data->set('label', $this->getLabel($options));
        $data->set('confirmMessage', $this->getConfirmMessage($options));
        $data->set('position', $options['position']);
        $data->set('component', $options['component']);
    }

    private function getLabel($options): string
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }

    private function getConfirmMessage($options): ?string
    {
        if($options['confirm_message'] !== null) {
            return $this->translator->trans($options['confirm_message'], [], $options['translation_domain']);
        }
        return null;
    }

    public function getPermission(array $options, EntityRepositoryInterface $repository): mixed
    {
        return $options['permission'];
    }

    public function isEnabled(array $options): bool
    {
        return $options['hidden'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'permission'  => null,
            'position'  => 0,
            'translation_domain' => null,
            'hidden' => false,
            'confirm_message' => null,
            'component' => 'batch-url',
            'route' => null,
            'route_parameters' => null,
        ]);

        $resolver->setRequired(['label']);
    }

    public static function getParentType(): ?string
    {
        return null;
    }
}
