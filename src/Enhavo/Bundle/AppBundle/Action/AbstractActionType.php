<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\AppBundle\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractActionType implements ActionTypeInterface
{
    /** @var TranslatorInterface */
    protected $translator;

    /** @var ActionLanguageExpression */
    private $actionLanguageExpression;

    /**
     * AbstractActionType constructor.
     * @param TranslatorInterface $translator
     * @param ActionLanguageExpression $actionLanguageExpression
     */
    public function __construct(TranslatorInterface $translator, ActionLanguageExpression $actionLanguageExpression)
    {
        $this->translator = $translator;
        $this->actionLanguageExpression = $actionLanguageExpression;
    }

    public function isHidden(array $options, $resource = null)
    {
        if ($options['hidden'] === false && $options['condition']) {
            $hidden = !$this->actionLanguageExpression->evaluate($options['condition'], [
                'resource' => $resource,
                'action' => $this
            ]);
        } else if (preg_match('/^exp:/', $options['hidden'])) {
            $hidden = $this->actionLanguageExpression->evaluate(substr($options['hidden'], 4), [
                'resource' => $resource,
                'action' => $this
            ]);
        } else {
            $hidden = $options['hidden'];
        }
        return $hidden;
    }

    public function getPermission(array $options, $resource = null)
    {
        return $options['permission'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon' => null,
            'translation_domain' => null,
            'label' => null,
            'permission' => null,
            'hidden' => false,
            'condition' => null
        ]);

        $resolver->setRequired([
            'component'
        ]);
    }

    public function createViewData(array $options, $resource = null)
    {
        $data = [
            'component' => $options['component'],
            'icon' => $options['icon'],
            'label' => $this->getLabel($options),
        ];

        return $data;
    }

    protected function getLabel(array $options)
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }
}
