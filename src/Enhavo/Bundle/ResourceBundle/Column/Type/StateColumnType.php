<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Contracts\Translation\TranslatorInterface;

class StateColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {
        $propertyAccessor = new PropertyAccessor();

        $value = $propertyAccessor->getValue($resource, $options['property']);

        $stateMap = $options['states'];

        if (isset($stateMap[$value])) {
            $color = $stateMap[$value]['color'] ?? '';
            $label = $stateMap[$value]['label'] ?? '';
            $translationDomain = $stateMap[$value]['translation_domain'] ?? $options['translation_domain'];


            $data->set('value', $this->translator->trans($label, [], $translationDomain));
            $data->set('color', $color);
        } else {
            $data->set('value', $value);
            $data->set('color', '');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-state',
            'model' => 'StateColumn',
            'states' => [],
            'sortingProperty' => null,
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'state';
    }
}
