<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Filter\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Exception\FilterException;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class OptionType extends AbstractFilterType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createViewData($options, Data $data): void
    {
        $choices = $this->formatChoices($options);
        $this->validateInitialValue($options['initial_value'], $choices);

        $data->add([
            'choices' => $choices
        ]);
    }

    private function formatChoices($options): array
    {
        $data = [];
        foreach($options['options'] as $value => $label) {
            $data[] = [
                'label' => $this->translator->trans($label, [], $options['translation_domain']),
                'code' => $value,
            ];
        }
        return $data;
    }

    private function validateInitialValue($value, $choices): void
    {
        if ($value === null) {
            return;
        }
        foreach($choices as $choice) {
            if ($choice['code'] == $value) {
                return;
            }
        }

        throw new \InvalidArgumentException('Parameter "initial_value" must either be null or one of keys in parameter "options"');
    }

    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        if ($value === null || trim($value) === '') {
            return;
        }

        $possibleValues = $options['options'];
        $possibleValues = array_keys($possibleValues);
        $findPossibleValue = false;
        foreach($possibleValues as $possibleValue) {
            if ($possibleValue == $value) {
                $findPossibleValue = true;
                break;
            }
        }

        if (!$findPossibleValue) {
            throw new FilterException('Value does not exist in options');
        }

        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);
        $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value, $propertyPath);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'filter-option',
            'model' => 'OptionFilter',
            'initial_value' => null,
        ]);
        $resolver->setRequired(['options', 'property']);
    }

    public static function getName(): ?string
    {
        return 'option';
    }
}
