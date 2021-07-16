<?php
/**
 * DeleteButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */
namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionLanguageExpression;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Contracts\Translation\TranslatorInterface;

class DeleteActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    /**
     * @var CsrfTokenManager
     */
    private $tokenManager;

    public function __construct(TranslatorInterface $translator, ActionLanguageExpression $actionLanguageExpression, RouterInterface $router, CsrfTokenManager $tokenManager)
    {
        parent::__construct($translator, $actionLanguageExpression, $router);
        $this->tokenManager = $tokenManager;
    }

    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        $data = array_merge($data, [
            'confirm' => $options['confirm'],
            'confirm_message' => $this->translator->trans($options['confirm_message'], [], $options['translation_domain']),
            'confirm_label_ok' => $this->translator->trans($options['confirm_label_ok'], [], $options['translation_domain']),
            'confirm_label_cancel' => $this->translator->trans($options['confirm_label_cancel'], [], $options['translation_domain']),
            'token' => $this->tokenManager->getToken($resource->getId())->getValue()
        ]);

        return $data;
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
            'append_id' => true
        ]);
    }

    public function getType()
    {
        return 'delete';
    }
}
