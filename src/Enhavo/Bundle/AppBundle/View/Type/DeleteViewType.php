<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Symfony\Contracts\Translation\TranslatorInterface;

class DeleteViewType extends AbstractViewType
{
    /** @var FlashBag */
    protected $flashBag;

    private $translator;

    /**
     * CreateViewer constructor.
     * @param RequestConfigurationFactory $requestConfigurationFactory
     * @param ViewerUtil $util
     * @param FlashBag $flashBag
     * @param TranslatorInterface $translator
     */
    public function __construct(
        RequestConfigurationFactory $requestConfigurationFactory,
        ViewerUtil $util,
        FlashBag $flashBag,
        TranslatorInterface $translator
    )
    {
        parent::__construct($requestConfigurationFactory, $util);
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    public function getType()
    {
        return 'delete';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        $label = $this->mergeConfig([
            $options['label'],
            $this->getViewerOption('label', $requestConfiguration)
        ]);

        $parameters->set('data', [
            'messages' => $this->getFlashMessages(),
            'view' => [
                'id' => $this->getViewId(),
                'label' => $this->container->get('translator')->trans($label, [], $parameters->get('translation_domain'))
            ]
        ]);

        $parameters->set('resource', $options['resource']);
    }

    private function getFlashMessages()
    {
        $messages = [];
        $types = ['success', 'error', 'notice', 'warning'];
        foreach($types as $type) {
            foreach($this->flashBag->get($type) as $message) {
                $messages[] = [
                    'message' => $this->translator->trans(is_array($message) ? $message['message'] : $message),
                    'type' => $type
                ];
            }
        }
        return $messages;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'javascripts' => [
                'enhavo/app/delete'
            ],
            'stylesheets' => [
                'enhavo/app/delete'
            ],
        ]);
    }
}
