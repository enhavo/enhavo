<?php
/**
 * DeleteButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */
namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        $data = array_merge($data, [
            'confirm' => $options['confirm'],
            'confirm_message' => $this->translator->trans($options['confirm_message'], [], $options['translation_domain']),
            'confirm_label_ok' => $this->translator->trans($options['confirm_label_ok'], [], $options['translation_domain']),
            'confirm_label_cancel' => $this->translator->trans($options['confirm_label_cancel'], [], $options['translation_domain']),
        ]);

        return $data;
    }

    protected function getUrl(array $options, $resource = null)
    {
        return $this->router->generate($options['route'], array_merge_recursive([
            'id' => $resource->getId(),
        ], $options['route_parameters']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'delete-action',
            'label' => 'label.delete',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'delete',
            'confirm' => true,
            'confirm_message' => 'message.delete.confirm',
            'confirm_label_ok' => 'label.ok',
            'confirm_label_cancel' => 'label.cancel',
        ]);
    }

    public function getType()
    {
        return 'delete';
    }
}
