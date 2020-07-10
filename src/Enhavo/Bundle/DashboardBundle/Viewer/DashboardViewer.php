<?php
/**
 * DashboardViewer.php
 *
 * @since 12/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\DashboardBundle\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\AbstractViewer;
use Enhavo\Bundle\DashboardBundle\Widget\WidgetManager;
use FOS\RestBundle\View\View;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardViewer extends AbstractViewer
{
    use ContainerAwareTrait;

    /**
     * @var WidgetManager
     */
    private $widgetManager;

    /**
     * constructor
     * @param WidgetManager $widgetManager
     */
    public function __construct(WidgetManager $widgetManager)
    {
        $this->widgetManager = $widgetManager;
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
        $templateVars['data']['widgets'] = $this->widgetManager->createViewData();
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
            'translation_domain' => 'EnhavoDashboardBundle',
            'data' => null
        ]);
    }
}
