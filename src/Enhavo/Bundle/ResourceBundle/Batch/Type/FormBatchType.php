<?php

namespace Enhavo\Bundle\ResourceBundle\Batch\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormBatchType extends AbstractBatchType
{
    public function __construct(
        private readonly VueForm $vueForm,
        private readonly FormFactoryInterface $formFactory,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    )
    {
    }

    public function createViewData(array $options, Data $data): void
    {
        $form = $this->formFactory->create(
            $this->expressionLanguage->evaluate($options['form']),
            $this->expressionLanguage->evaluateArray($options['form_options']),
        );

        $data['form'] = $this->vueForm->createData($form->createView());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'batch-form',
            'model' => 'FormBatch',
            'form_options' => [],
        ]);

        $resolver->setRequired(['form']);
    }
}
