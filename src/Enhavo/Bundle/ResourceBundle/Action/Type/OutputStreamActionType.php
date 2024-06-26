<?php

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class OutputStreamActionType extends AbstractActionType
{
    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $data->set('modal', [
            'component' => 'output-stream',
            'url' => $data['url'],
            'closeLabel' => $this->translator->trans('label.close', [], 'EnhavoAppBundle')
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'modal-action',
        ]);

        $resolver->setRequired(['route']);
    }

    public static function getParentType(): ?string
    {
        return UrlActionType::class;
    }

    public static function getName(): ?string
    {
        return 'output_stream';
    }
}
