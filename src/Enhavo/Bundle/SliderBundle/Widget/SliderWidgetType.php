<?php
/**
 * SliderWidgetType.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SliderBundle\Widget;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SliderWidgetType extends AbstractWidgetType
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'slider';
    }

    public function createViewData(array $options, $resource = null)
    {
        $repository = $this->container->get('enhavo_slider.repository.slide');
        $slides = $repository->findPublished();

        return [
            'slides' => $slides,
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'template' => 'theme/widget/slider.html.twig',
        ]);
    }
}
