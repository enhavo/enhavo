<?php

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\Delete;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AbstractFormEndpointType;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Form\Data\DeleteConfirm;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DeleteConfirmEndpointType extends AbstractFormEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly UserManager $userManager,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    protected function init($options, Request $request, Data $data, Context $context): void
    {
        $user = $this->getUser();

        if ($user === null) {
            throw $this->createAccessDeniedException();
        }
    }

    protected function getForm($options, Request $request, Data $data, Context $context): FormInterface
    {
        $configuration = $this->provider->getDeleteConfirmConfiguration();
        $deleteConfirm = new DeleteConfirm();
        return $this->createForm($configuration->getFormClass(), $deleteConfirm, $configuration->getFormOptions());
    }

    protected function handleSuccess($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $configuration = $this->provider->getDeleteConfirmConfiguration();

        /** @var UserInterface $user */
        $user = $this->getUser();
        $this->userManager->logout();
        $this->userManager->delete($user, $configuration);
        $data->set('message', $this->translator->trans('reset_password.flash.message.success', [], 'EnhavoUserBundle'));
    }

    protected function handleFailed($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $data->set('message', $this->translator->trans('reset_password.flash.message.error', [], 'EnhavoUserBundle'));
    }

    protected function getRedirectUrl($options, Request $request, Data $data, Context $context, FormInterface $form): ?string
    {
        $configuration = $this->provider->getDeleteConfirmConfiguration();
        return $this->generateUrl($configuration->getRedirectRoute());
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $configuration = $this->provider->getDeleteConfirmConfiguration();

        $resolver->setDefaults([
            'template' => $this->resolveTemplate($configuration->getTemplate()),
        ]);
    }
}
