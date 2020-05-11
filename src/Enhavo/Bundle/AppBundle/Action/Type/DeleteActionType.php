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
use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;
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

    public function __construct(TranslatorInterface $translator, RouterInterface $router, CsrfTokenManager $tokenManager)
    {
        parent::__construct($translator, $router);
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
            'component' => 'delete-action',
            'label' => 'label.delete',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'delete',
            'confirm' => true,
            'confirm_message' => 'message.delete.confirm',
            'confirm_label_ok' => 'label.ok',
            'confirm_label_cancel' => 'label.cancel'
        ]);
    }

    public function getType()
    {
        return 'delete';
    }
}
