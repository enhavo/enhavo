<?php
/**
 * ImageCropperViewer.php
 *
 * @since 11/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Viewer;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Viewer\AbstractActionViewer;
use Enhavo\Bundle\MediaBundle\Media\ImageCropperManager;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Media\UrlResolver;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryViewer extends AbstractActionViewer
{
    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * ImageCropperViewer constructor.
     * @param ActionManager $actionManager
     * @param FlashBag $flashBag
     */
    public function __construct(
        ActionManager $actionManager,
        FlashBag $flashBag
    ) {
        parent::__construct($actionManager);
        $this->flashBag = $flashBag;
    }

    public function getType()
    {
        return 'media_library';
    }

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = parent::createView($options);
        $templateVars = $view->getTemplateData();
        $templateVars['data']['items'] = [];
        $view->setTemplateData($templateVars);
        return $view;
    }

    protected function createActions($options)
    {
        $default = [
            'save' => [
                'type' => 'save'
            ],
        ];

        return $default;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'javascripts' => [
                'enhavo/media-library'
            ],
            'stylesheets' => [
                'enhavo/media-library'
            ],
        ]);
    }
}
