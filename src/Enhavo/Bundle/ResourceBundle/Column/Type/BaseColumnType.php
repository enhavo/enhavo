<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:18
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\Column;
use Enhavo\Bundle\ResourceBundle\Column\ColumnTypeInterface;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseColumnType extends AbstractType implements ColumnTypeInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    )
    {
    }

    public function buildSortingQuery($options, FilterQuery $query, string $direction): void
    {
        if ($options['sortable'] && isset($options['property']) && in_array($direction, [Column::SORTING_DIRECTION_ASC, Column::SORTING_DIRECTION_DESC])) {
            $propertyPath = explode('.', $options['property']);
            $topProperty = array_pop($propertyPath);
            $query->addOrderBy($topProperty, $direction, $propertyPath);
        }
    }

    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {

    }

    public function createColumnViewData(array $options, Data $data): void
    {
        $data->set('label', $this->translator->trans($options['label'], [], $options['translation_domain']));
        $data->set('width', $options['width']);
        $data->set('component',  $options['component']);
        $data->set('model',  $options['model']);
        $data->set('sortable', $options['sortable'] ?? false);
        $data->set('visibleCondition', $options['visible_condition']);
        $data->set('visible', $options['visible']);
    }

    public function getPermission(array $options): mixed
    {
        return $this->expressionLanguage->evaluate($options['permission']);
    }

    public function isEnabled(array $options): bool
    {
        return $this->expressionLanguage->evaluate($options['enabled']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => '',
            'translation_domain' => null,
            'width' => 1,
            'sortable' => false,
            'visible_condition' => null,
            'visible' => null,
            'permission' => null,
            'enabled' => true,
        ]);

        $resolver->setRequired('component');
        $resolver->setRequired('model');
    }

    public static function getName(): ?string
    {
        return 'base';
    }
}
