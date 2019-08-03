<?php
/**
 * DashboardViewer.php
 *
 * @since 12/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\DashboardBundle\Viewer;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Viewer\AbstractActionViewer;
use FOS\RestBundle\View\View;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardViewer extends AbstractActionViewer
{
    use ContainerAwareTrait;

    /**
     * ImageCropperViewer constructor.
     * @param ActionManager $actionManager
     */
    public function __construct(
        ActionManager $actionManager
    ) {
        parent::__construct($actionManager);
    }

    public function getType()
    {
        return 'dashboard';
    }

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = parent::createView($options);
        $templateVars = $view->getTemplateData();
        $view->setTemplateData($templateVars);
        return $view;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'javascripts' => [
                'enhavo/dashboard'
            ],
            'stylesheets' => [
                'enhavo/dashboard'
            ],
            'label' => 'dashboard.label.dashboard',
            'translation_domain' => 'EnhavoDashboardBundle'
        ]);
    }
}
