<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-18
 * Time: 17:43
 */

namespace Enhavo\Bundle\AppBundle\Menu\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DropdownMenuType extends AbstractMenuType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createViewData(array $options, Data $data): void
    {
        $data->add([
            'choices' => $this->formatChoices($options['choices'], $options['translation_domain']),
            'label' => $this->translator->trans($options['label'], [], $options['translation_domain']),
            'value' => $options['value'],
            'selectedValue' => $this->getInitialValue($options['value'], $options['choices'], $options['translation_domain']),
        ]);
    }

    private function formatChoices(array $choices, $translationDomain): array
    {
        $data = [];
        foreach ($choices as $value => $label) {
            $data[] = [
                'label' => $this->translator->trans($label, [], $translationDomain),
                'code' => $value,
            ];
        }
        return $data;
    }

    private function getInitialValue($value, array $choices, $translationDomain): ?array
    {
        foreach ($choices as $code => $label) {
            if ($value == $code) {
                return [
                    'label' => $this->translator->trans($label, [], $translationDomain),
                    'code' => $value,
                ];
            }
        }
        return null;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => 'DropdownMenuItem',
            'translation_domain' => null,
            'component' => 'menu-dropdown',
            'value' => null,
            'label' => null,
        ]);

        $resolver->setRequired([
            'choices',
        ]);
    }

    public static function getName(): ?string
    {
        return 'dropdown';
    }
}
