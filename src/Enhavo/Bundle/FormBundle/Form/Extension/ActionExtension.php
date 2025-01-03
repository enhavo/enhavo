<?php

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionExtension extends AbstractTypeExtension
{
    /** @var ActionManager */
    private $actionManager;

    /**
     * ActionExtension constructor.
     * @param ActionManager $actionManager
     */
    public function __construct(ActionManager $actionManager)
    {
        $this->actionManager = $actionManager;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'actions' => []
        ]);
    }

    public function buildView(FormView $view, FormInterface$form, array $options)
    {
        $actions = $options['actions'];
        if(!empty($actions)){
            $data = $form->getParent() && $form->getParent()->getData() ? $form->getParent()->getData() : null;
            $actions = $this->createActionsViewData($actions, $data);
        }

        $view->vars['actions'] = $actions;
    }

    private function createActionsViewData(array $options, $resource)
    {
        $data = [];
        $actions = $this->actionManager->getActions($options, $resource);
        foreach ($actions as $key => $action) {
            $data[$key] = $action->createViewData($resource);
        }
        return $data;
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
