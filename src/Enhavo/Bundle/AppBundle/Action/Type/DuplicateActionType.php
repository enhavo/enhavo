<?php
namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DuplicateActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        $data = array_merge($data, [
            'confirm' => $options['confirm'],
            'confirmMessage' => $this->translator->trans($options['confirm_message'], [], $options['translation_domain']),
            'confirmLabelOk' => $this->translator->trans($options['confirm_label_ok'], [], $options['translation_domain']),
            'confirmLabelCancel' => $this->translator->trans($options['confirm_label_cancel'], [], $options['translation_domain']),
        ]);

        return $data;
    }

    protected function getUrl(array $options, $resource = null)
    {
        $parameters = [];
        if(!isset($options['route_parameters']['id'])) {
            $parameters['id'] = $resource->getId();
        }
        $parameters = array_merge_recursive($parameters, $options['route_parameters']);
        return $this->router->generate($options['route'], $parameters);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'duplicate-action',
            'label' => 'label.duplicate',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'content_copy',
            'confirm' => true,
            'confirm_message' => 'message.duplicate.confirm',
            'confirm_label_ok' => 'label.ok',
            'confirm_label_cancel' => 'label.cancel'
        ]);
    }

    public function getType()
    {
        return 'duplicate';
    }
}
