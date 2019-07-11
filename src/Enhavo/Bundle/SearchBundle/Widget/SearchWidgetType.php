<?php
/**
 * SearchWidgetType.php
 *
 * @since 10/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SearchBundle\Widget;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchWidgetType extends AbstractWidgetType
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'search';
    }

    public function createViewData($options, $resource = null)
    {
        return [];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'template' => 'theme/widget/search.html.twig'
        ]);
    }
}
