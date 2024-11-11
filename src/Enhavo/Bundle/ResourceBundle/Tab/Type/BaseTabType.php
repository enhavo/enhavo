<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\ResourceBundle\Tab\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Bundle\ResourceBundle\Tab\TabTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseTabType extends AbstractType implements TabTypeInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    )
    {
    }

    public function createViewData(array $options, Data $data, InputInterface $input): void
    {
        $data->set('component', $options['component']);
        $data->set('model', $options['model']);
        $data->set('label', $this->translator->trans($options['label'], [], $options['translation_domain']));
    }

    public function isEnabled(array $options, InputInterface $input): bool
    {
        return !!$this->expressionLanguage->evaluate($options['enabled'], [
            'input' => $input,
            'tab' => $this
        ]);
    }

    public function getPermission(array $options, InputInterface $input): mixed
    {
        return $this->expressionLanguage->evaluate($options['permission'], [
            'input' => $input,
            'tab' => $this
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => null,
            'permission' => null,
            'enabled' => true,
            'model' => 'BaseTab',
            'label' => null,
        ]);

        $resolver->setRequired([
            'component',
        ]);
    }
}
