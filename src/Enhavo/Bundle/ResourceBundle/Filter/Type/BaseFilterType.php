<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Filter\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Filter\FilterTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseFilterType extends AbstractType implements FilterTypeInterface
{
    public function __construct(
        private readonly ResourceExpressionLanguage $expressionLanguage,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createViewData($options, Data $data): void
    {
        $data->set('component', $options['component']);
        $data->set('label', $this->translator->trans($options['label'], [], $options['translation_domain']));
    }

    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {

    }

    public function getPermission($options): mixed
    {
        return $this->expressionLanguage->evaluate($options['permission'], [
            'options' => $options
        ]);
    }

    public function isEnabled($options): bool
    {
        return $this->expressionLanguage->evaluate($options['enabled'], [
            'options' => $options
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'enabled' => true,
            'permission' => null,
            'translation_domain' => null,
        ]);

        $resolver->setRequired('component');
        $resolver->setRequired('label');
    }

    public static function getName(): ?string
    {
        return 'base';
    }
}
