<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ExportModalActionType
 * @package SummitDatabaseBundle\Action
 */
class OpenActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    /**
     * @param array $options
     * @param null $resource
     * @return array|string
     */
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);
        $data = array_merge($data, [
            'target' => $options['target'],
            'key' => $options['view_key']
        ]);
        return $data;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'open-action',
            'label' => 'label.open',
            'icon' => 'arrow_forward',
            'target' => '_self',
            'view_key' => null
        ]);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'open';
    }
}
