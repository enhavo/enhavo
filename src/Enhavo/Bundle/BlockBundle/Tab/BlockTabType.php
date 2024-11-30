<?php

namespace Enhavo\Bundle\BlockBundle\Tab;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Bundle\ResourceBundle\Tab\AbstractTabType;
use Enhavo\Bundle\ResourceBundle\Tab\Type\FormTabType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockTabType extends AbstractTabType
{
    public function __construct(
        private ActionManager $actionManager,
    )
    {
    }

    public function createViewData(array $options, Data $data, InputInterface $input = null): void
    {
        $data['property'] = $options['property'];
        $data['actions'] = $this->actionManager->createViewData($options['actions']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
       $resolver->setDefaults([
           'label' => 'content.label.content',
           'translation_domain' =>  'EnhavoContentBundle',
           'component' => 'tab-block',
           'model' => 'BlockTab',
           'actions' => [
               'collapse' => [
                   'type' => 'block_collapse'
               ]
           ],
       ]);

       $resolver->setNormalizer('actions', function ($options, $value) {
           if (isset($value['collapse']['type']) &&
               $value['collapse']['type'] === 'block_collapse' &&
               !isset($value['collapse']['property'])
           ) {
               $value['collapse']['property'] = $options['property'];
           }

           return $value;
       });

        $resolver->setRequired(['property']);
    }

    public static function getParentType(): ?string
    {
        return FormTabType::class;
    }

    public static function getName(): ?string
    {
        return 'block';
    }
}
