<?php
/**
 * DashboardViewer.php
 *
 * @since 12/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\DashboardBundle\View;

use AppViewType;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\DashboardBundle\Widget\WidgetManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardViewType extends AbstractViewType
{
    /** @var WidgetManager */
    private $widgetManager;

    /**
     * constructor
     * @param WidgetManager $widgetManager
     */
    public function __construct(WidgetManager $widgetManager)
    {
        $this->widgetManager = $widgetManager;
    }

    public static function getName(): ?string
    {
        return 'dashboard';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function createViewData($options, ViewData $viewData)
    {
        $viewData['widgets'] = $this->widgetManager->createViewData();
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'application' => '@enhavo/dashboard/dashboard/DashboardApp',
            'component' => '@enhavo/dashboard/components/ApplicationComponent.vue',
            'label' => 'dashboard.label.dashboard',
            'translation_domain' => 'EnhavoDashboardBundle',
        ]);
    }
}
