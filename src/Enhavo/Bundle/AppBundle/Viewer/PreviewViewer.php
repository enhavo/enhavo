<?php
/**
 * PreviewViewer.php
 *
 * @since 18/11/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

class PreviewViewer extends AbstractViewer
{
    public function getDefaultConfig()
    {
        return array(
            'strategy' => 'service',
            'service' => 'enhavo_app.preview.default_renderer:renderTest'
        );
    }

    public function getStrategyName()
    {
        return $this->getConfig()->get('strategy');
    }
}
