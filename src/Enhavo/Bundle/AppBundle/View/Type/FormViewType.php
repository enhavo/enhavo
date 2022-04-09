<?php
/**
 * @author blutze-media
 * @since 2022-04-09
 */

/**
 * UpdateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormViewType extends AbstractViewType
{
    public function __construct(
        private array                  $formThemes,
        private ActionManager          $actionManager,
        private FlashBag               $flashBag,
        private ViewUtil               $util,
        private TranslatorInterface    $translator
    ) {}

    public static function getName(): ?string
    {
        return 'form';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    public function handleRequest($options, Request $request, ViewData $viewData, ViewData $templateData)
    {
        $this->util->updateRequest();
        $configuration = $this->util->getRequestConfiguration($options);
        $this->util->isGrantedOr403($configuration, ResourceActions::UPDATE);

        $resource = $options['resource'];
        /** @var Form $form */
        $form = $options['form'];
        $form->handleRequest($request);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH']) && $form->isSubmitted()) {
            if ($form->isValid()) {
                $resource = $form->getData();
                $options['manager']->update($resource);
                $this->flashBag->add('success', $this->translator->trans($options['success_message'], [], $options['translation_domain']));

            } else {
                $this->flashBag->add('error', $this->translator->trans($options['error_message'], [], $options['translation_domain']));
                foreach ($form->getErrors() as $error) {
                    $this->flashBag->add('error', $error->getMessage());
                }
            }
        }
        $viewData['resource'] = $resource;
        $viewData['messages'] = array_merge($viewData['messages'], $this->util->getFlashMessages());
        $templateData['form'] = $form->createView();

        return null;
    }

    public function createViewData($options, ViewData $data)
    {
        $actionsSecondary = $this->util->mergeConfigArray([
            $this->createActionsSecondary()
        ]);

        $data['actionsSecondary'] = $this->actionManager->createActionsViewData($actionsSecondary, $options['resource']);

        $data['messages'] = [];
        $data['form_themes'] = $options['form_themes'];
    }

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {
        $templateData->set('form_themes', $viewData['form_themes']);
        if ($options['tabs'] == null) {
            $tabs = [
                'main' => [
                    'label' => '',
                    'template' => $options['form_template']
                ],
            ];
        } else {
            $tabs = $options['tabs'];
        }
        $templateData->set('tabs', $tabs);
    }

    private function createActionsSecondary(): array
    {
        return [
            'save' => [
                'type' => 'save',
            ]
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setRequired(['manager']);

        $optionsResolver->setDefaults([
            'entrypoint' => 'enhavo/app/form',
            'form' => null,
            'form_themes' =>$this->formThemes,
            'form_template' => 'admin/view/form-template.html.twig',
            'tabs' => null,
            'request' => null,
            'request_configuration' => null,
            'resource' => [],
            'label' => 'label.edit',
            'success_message' => 'success',
            'error_message' => 'error',
            'translation_domain' => 'EnhavoAppBundle'
        ]);
    }
}
