<?php
/**
 * ResourcePreviewViewer.php
 *
 * @since 18/11/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Preview\StrategyResolver;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\Viewer\AbstractResourceViewer;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourcePreviewViewType extends AbstractViewType
{
    /**
     * @var StrategyResolver
     */
    private $strategyResolver;

    public function __construct(RequestConfigurationFactory $requestConfigurationFactory, ViewerUtil $util, StrategyResolver $strategyResolver)
    {
        parent::__construct($requestConfigurationFactory, $util);
        $this->strategyResolver = $strategyResolver;
    }

    public function getType()
    {
        return 'resource_preview';
    }

    private function createResponse(array $options): Response
    {
        $strategyName = $this->mergeConfig([
            $options['strategy'],
            $this->getViewerOption('strategy', $options['request_configuration'])
        ]);

        $strategyOptions = $this->mergeConfigArray([
            ['service' => 'enhavo_app.preview.default_renderer:render'],
            $options['strategy_options'],
            $this->getViewerOption('strategy_options', $options['request_configuration'])
        ]);

        $strategy = $this->strategyResolver->getStrategy($strategyName);
        $response = $strategy->getPreviewResponse($options['resource'], $strategyOptions);

        return $response;
    }

    public function createView($options = []): View
    {
        $view = View::create();
        $view->setResponse($this->createResponse($options));
        $view->setFormat('preview');
        return $view;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'strategy' => 'service',
            'strategy_options' => null
        ]);
        $optionsResolver->setRequired(['request_configuration', 'request', 'form']);
    }
}
