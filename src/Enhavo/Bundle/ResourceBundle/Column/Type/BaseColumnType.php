<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:18
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\ColumnTypeInterface;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
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

    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {

    }

    public function createColumnViewData(array $options, Data $data): void
    {
        $data->set('label', $this->translator->trans($options['label'], [], $options['translation_domain']));
        $data->set('width', $options['width']);
        $data->set('component',  $options['component']);
        $data->set('sortable', $options['sortable'] ?? false);
        $data->set('condition', $options['condition']);
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
            'condition' => null,
            'permission' => null,
            'enabled' => true,
        ]);
    }

    public static function getName(): ?string
    {
        return 'base';
    }
}
